<?php

	class BittrexWrapper
	{
		private $baseUrl;
		private $apiVersion = 'v1.1';
		private $apiKey;
		private $apiSecret;


		public function __construct ($apiKey, $apiSecret)
		{
			$this->apiKey = $apiKey;
			$this->apiSecret = $apiSecret;
			$this->baseUrl = 'https://bittrex.com/api/'. $this->apiVersion .'/';
		}


		private function Call($method, $parameters = new array(), $apiKey = false)
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
			
		}


		/*
			Used to get all supported currencies at Bittrex along with other meta data.
		*/
		public function GetCurrencies()
		{
			
		}


		/*
			Used to get the current tick values for a market.
		*/
		public function GetTicker($market)
		{

		}


		/*
			Used to get the last 24 hour summary of all active exchanges.
		*/
		public function GetMarketSummaries()
		{

		}


		/*
			Used to get the last 24 hour summary for specific active exchanges.
		*/
		public function GetMarketSummary($market)
		{

		}


		/*
			Used to get retrieve the orderbook for a given market.
		*/
		public function GetOrderBook($market, $type)
		{

		}


		/*
			Used to retrieve the latest trades that have occured for a specific market.
		*/
		public function GetMarketHistory($market)
		{

		}


		/*
			Used to place a buy order in a specific market.
			Make sure you have the proper permissions set on your API keys for this call to work.
		*/
		public function BuyLimit($market, $quantity, $rate)
		{

		}


		/*
			Used to place an sell order in a specific market.
			Make sure you have the proper permissions set on your API keys for this call to work.
		*/
		public function SellLimit($market, $quantity, $rate)
		{

		}


		/*
			Used to cancel a buy or sell order.
		*/
		public function Cancel($uuid)
		{

		}


		/*
			Get all orders that you currently have opened.
			A specific market can be requested.
		*/
		public function GetOpenOrders($market = null)
		{

		}


		/*
			Used to retrieve all balances from your account.
		*/
		public function GetBalances()
		{

		}


		/*
			Used to retrieve the balance from your account for a specific currency.
		*/
		public function GetBalance($currency)
		{

		}


		/*
			Used to retrieve or generate an address for a specific currency.
			If one does not exist, the call will fail and return ADDRESS_GENERATING until one is available.
		*/
		public function GetDepositAddress($currency)
		{

		}


		/*
			Used to withdraw funds from your account.
			note: please account for txfee.
		*/
		public function Withdraw($currency, $quantity, $address, $paymentid)
		{

		}


		/*
			Used to retrieve a single order by uuid.
		*/
		public function GetOrder($uuid)
		{

		}


		/*
			Used to retrieve your order history.
		*/
		public function GetOrderHistory($market = null)
		{

		}


		/*
			Used to retrieve your withdrawal history.
		*/
		public function GetWithdrawalHistory($currency = null)
		{

		}


		/*	
			Used to retrieve your deposit history.
		*/
		public function GetDepositHistory($currency = null)
		{

		}
	}

?>