<?php

	class Bittrex
	{
		private $baseUrl;
		private $apiVersion = 'v1.1';
		private $apiKey;
		private $apiSecret;


		public function __construct ($apiKey, $apiSecret)
		{
			$this->apiKey = $apiKey;
			$this->apiSecret = $apiSecret;
			$this->baseUrl = 'https://bittrex.com/api/'.$this->apiVersion;
		}


		private function Call($method, $parameters = array(), $apiKey = false)
		{
			$uri = $this->baseUrl.$method;

			if($apiKey)
			{
				$parameters['apikey'] = $this->apiKey;
				$parameters['nonce'] = time();
			}

			if (!empty($parameters))
			{
				$uri .= '?'.http_build_query($parameters);
			}

			$curl = curl_init($uri);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('apisign:'.hash_hmac('sha512', $uri, $this->apiSecret)));
			$curlResult = curl_exec($curl);

			return json_decode($curlResult);
		}


		/*
			Used to get the open and available trading markets at Bittrex along with other meta data.
		*/
		public function GetMarkets()
		{
			return $this->Call('/public/getmarkets');
		}


		/*
			Used to get all supported currencies at Bittrex along with other meta data.
		*/
		public function GetCurrencies()
		{
			return $this->Call('/public/getcurrencies');
		}


		/*
			Used to get the current tick values for a market.
		*/
		public function GetTicker($market)
		{
			return $this->Call('/public/getticker', array('market' => $market));
		}


		/*
			Used to get the last 24 hour summary of all active exchanges.
		*/
		public function GetMarketSummaries()
		{
			return $this->Call('/public/getmarketsummaries');
		}


		/*
			Used to get the last 24 hour summary for specific active exchanges.
		*/
		public function GetMarketSummary($market)
		{
			return $this->Call('/public/getmarketsummary', array('market' => $market));
		}


		/*
			Used to get retrieve the orderbook for a given market.
		*/
		public function GetOrderBook($market, $type)
		{
			return $this->Call('/public/getorderbook', array('market' => $market, 'type' => $type));
		}


		/*
			Used to retrieve the latest trades that have occured for a specific market.
		*/
		public function GetMarketHistory($market)
		{
			return $this->Call('/public/getmarkethistory', array('market' => $market));
		}


		/*
			Used to place a buy order in a specific market.
			Make sure you have the proper permissions set on your API keys for this call to work.
		*/
		public function BuyLimit($market, $quantity, $rate)
		{
			return $this->Call('/market/buylimit', array('market' => $market, 'quantity' => $quantity, 'rate' => $rate), true);
		}


		/*
			Used to place an sell order in a specific market.
			Make sure you have the proper permissions set on your API keys for this call to work.
		*/
		public function SellLimit($market, $quantity, $rate)
		{
			return $this->Call('/market/selllimit', array('market' => $market, 'quantity' => $quantity, 'rate' => $rate), true);
		}


		/*
			Used to cancel a buy or sell order.
		*/
		public function Cancel($uuid)
		{
			return $this->Call('/market/cancel', array('uuid' => $uuid), true);
		}


		/*
			Get all orders that you currently have opened.
			A specific market can be requested.
		*/
		public function GetOpenOrders($market = null)
		{
			return $this->Call('/market/getopenorders', array('market' => $market), true);
		}


		/*
			Used to retrieve all balances from your account.
		*/
		public function GetBalances()
		{
			return $this->Call('/account/getbalances', array(), true);
		}


		/*
			Used to retrieve the balance from your account for a specific currency.
		*/
		public function GetBalance($currency)
		{
			return $this->Call('/account/getbalance', array('currency' => $currency), true);
		}


		/*
			Used to retrieve or generate an address for a specific currency.
			If one does not exist, the call will fail and return ADDRESS_GENERATING until one is available.
		*/
		public function GetDepositAddress($currency)
		{
			return $this->Call('/account/getdepositaddress', array('currency' => $currency), true);
		}


		/*
			Used to withdraw funds from your account.
			note: please account for txfee.
		*/
		public function Withdraw($currency, $quantity, $address, $paymentid)
		{
			$parameters = array(
				'currency' => $currency,
				'quantity' => $quantity,
				'address' => $address
			);

			if($paymentid)
			{
				$parameters['paymentid'] = $paymentid;
			}

			return $this->Call('/account/withdraw', $parameters, true);
		}


		/*
			Used to retrieve a single order by uuid.
		*/
		public function GetOrder($uuid)
		{
			return $this->Call('/account/getorder', array('uuid' => $uuid), true);
		}


		/*
			Used to retrieve your order history.
		*/
		public function GetOrderHistory($market = null)
		{
			$parameters = array();

			if($market)
			{
				$parameters['market'] = $market;
			}

			return $this->Call('/account/getorderhistory', $parameters, true);
		}


		/*
			Used to retrieve your withdrawal history.
		*/
		public function GetWithdrawalHistory($currency = null)
		{
			$parameters = array();

			if($currency)
			{
				$parameters['currency'] = $currency;
			}

			return $this->Call('/account/getwithdrawalhistory', $parameters, true);
		}


		/*	
			Used to retrieve your deposit history.
		*/
		public function GetDepositHistory($currency = null)
		{
			$parameters = array();

			if($currency)
			{
				$parameters['currency'] = $currency;
			}

			return $this->Call('/account/getdeposithistory', $parameters, true);
		}
	}
?>