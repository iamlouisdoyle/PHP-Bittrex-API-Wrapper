<?php

	class Bittrex
	{
		private $m_baseUrl;
		private $m_apiVersion = 'v1.1';
		private $m_apiKey;
		private $m_apiSecret;


		public function __construct ($p_apiKey, $p_apiSecret)
		{
			$this->m_apiKey = $p_apiKey;
			$this->m_apiSecret = $p_apiSecret;
			$this->m_baseUrl = 'https://bittrex.com/api/'.$this->m_apiVersion;
		}


		private function Call($p_function, $p_parameters = array(), $p_usingApiKey = false)
		{
			$l_uri = $this->m_baseUrl.$p_function;

			if($p_usingApiKey)
			{
				$p_parameters['apikey'] = $this->m_apiKey;
				$p_parameters['nonce'] = time();
			}

			if (!empty($p_parameters))
			{
				$l_uri .= '?'.http_build_query($p_parameters);
			}

			$l_curl = curl_init($l_uri);
				curl_setopt($l_curl, CURLOPT_HTTPHEADER, array('apisign:'.hash_hmac('sha512', $l_uri, $this->m_apiSecret)));
			$l_curlResult = curl_exec($l_curl);

			return json_decode($l_curlResult);
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
		public function GetTicker($p_market)
		{
			return $this->Call('/public/getticker', array('market' => $p_market));
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
		public function GetMarketSummary($p_market)
		{
			return $this->Call('/public/getmarketsummary', array('market' => $p_market));
		}


		/*
			Used to get retrieve the orderbook for a given market.
		*/
		public function GetOrderBook($p_market, $p_type)
		{
			return $this->Call('/public/getorderbook', array('market' => $p_market, 'type' => $p_type));
		}


		/*
			Used to retrieve the latest trades that have occured for a specific market.
		*/
		public function GetMarketHistory($p_market)
		{
			return $this->Call('/public/getmarkethistory', array('market' => $p_market));
		}


		/*
			Used to place a buy order in a specific market.
			Make sure you have the proper permissions set on your API keys for this call to work.
		*/
		public function BuyLimit($p_market, $p_quantity, $p_rate)
		{
			return $this->Call('/market/buylimit', array('market' => $p_market, 'quantity' => $p_quantity, 'rate' => $p_rate), true);
		}


		/*
			Used to place an sell order in a specific market.
			Make sure you have the proper permissions set on your API keys for this call to work.
		*/
		public function SellLimit($p_market, $p_quantity, $p_rate)
		{
			return $this->Call('/market/selllimit', array('market' => $p_market, 'quantity' => $p_quantity, 'rate' => $p_rate), true);
		}


		/*
			Used to cancel a buy or sell order.
		*/
		public function Cancel($p_uuid)
		{
			return $this->Call('/market/cancel', array('uuid' => $p_uuid), true);
		}


		/*
			Get all orders that you currently have opened.
			A specific market can be requested.
		*/
		public function GetOpenOrders($p_market = null)
		{
			return $this->Call('/market/getopenorders', array('market' => $p_market), true);
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
		public function GetBalance($p_currency)
		{
			return $this->Call('/account/getbalance', array('currency' => $p_currency), true);
		}


		/*
			Used to retrieve or generate an address for a specific currency.
			If one does not exist, the call will fail and return ADDRESS_GENERATING until one is available.
		*/
		public function GetDepositAddress($p_currency)
		{
			return $this->Call('/account/getdepositaddress', array('currency' => $p_currency), true);
		}


		/*
			Used to withdraw funds from your account.
			note: please account for txfee.
		*/
		public function Withdraw($p_currency, $p_quantity, $p_address, $p_paymentId = null)
		{
			$l_parameters = array(
				'currency' => $p_currency,
				'quantity' => $p_quantity,
				'address' => $p_address
			);

			if($p_paymentId)
			{
				$l_parameters['paymentid'] = $p_paymentId;
			}

			return $this->Call('/account/withdraw', $l_parameters, true);
		}


		/*
			Used to retrieve a single order by uuid.
		*/
		public function GetOrder($p_uuid)
		{
			return $this->Call('/account/getorder', array('uuid' => $p_uuid), true);
		}


		/*
			Used to retrieve your order history.
		*/
		public function GetOrderHistory($p_market = null)
		{
			$l_parameters = array();

			if($p_market)
			{
				$l_parameters['market'] = $p_market;
			}

			return $this->Call('/account/getorderhistory', $l_parameters, true);
		}


		/*
			Used to retrieve your withdrawal history.
		*/
		public function GetWithdrawalHistory($p_currency = null)
		{
			$l_parameters = array();

			if($p_currency)
			{
				$l_parameters['currency'] = $p_currency;
			}

			return $this->Call('/account/getwithdrawalhistory', $l_parameters, true);
		}


		/*	
			Used to retrieve your deposit history.
		*/
		public function GetDepositHistory($p_currency = null)
		{
			$l_parameters = array();

			if($p_currency)
			{
				$l_parameters['currency'] = $p_currency;
			}

			return $this->Call('/account/getdeposithistory', $l_parameters, true);
		}
	}
?>