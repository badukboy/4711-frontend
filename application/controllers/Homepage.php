<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Homepage should be a dashboard of sorts, showing some summary information:
 * $ spent purchasing inventory, $ received from sales, cost of sales ingredients
 * consumed. These are derived from the transaction logs.
 */
class Homepage extends Application
{
        /**
         * Default Constructor
         */
	function __construct()
	{
		parent::__construct();
                $this->data['pagetitle'] = 'Aromatherapy Store';
	}

	/**
	 * Homepage for our app
	 */
	public function index()
	{
            $userrole = $this->session->userdata('userrole');
            if ($userrole != 'admin') {
                $message = 'Welcome to our store!';
                $this->data['content'] = $message;
                $this->render();
                return;
            }
            // this is the view we want shown
            $this->data['pagebody'] = 'homepage';
			// this is the data we want loaded onto the view
			$this->invCal();
			$this->saleCal();
                        $this->countRecipy();
                        $this->countProduct();
			//this makes the view rendered
            $this->render();
	}
	/**
	* Function for calculating the amount spent on inventory
	*/
	public function invCal() {
	        $this->load->model('supplies');
            // gets a list of supplies
            $source = $this->supplies->all();
            $supplies = array ();
			
			//Total
			$totalCost = 0;
			
			//Calculates
            foreach ($source as $record)
            {
                $rUniut = intval(preg_replace('/[^0-9]+/', '', $record['receiving_unit']), 10);
                $rCost = intval(preg_replace('/[^0-9]+/', '', $record['receiving_cost']), 10);
                $inv = intval(preg_replace('/[^0-9]+/', '', $record['quantity']), 10);
                $totalCost += (($inv / $rUniut) * $rCost);
					
            }
			//Makes a data field
			$this->data['totalCost'] = (int)$totalCost;
	}
	/**
	* Function for calculating the received from sales
	*/
	public function saleCal() {
	        $this->load->model('supplies');
            // gets a list of supplies
            $source = $this->stock->all();
            $supplies = array ();
			
			//Total
			$totalInc = 0;
			
			//Calculates
            foreach ($source as $record)
            {
		$stock = intval(preg_replace('/[^0-9]+/', '', $record['quantity']), 10);
		$price = floatval(preg_replace('/[^0-9.+]/', '', $record['price']));
		$totalInc += ($stock * $price);
					
            }
	    //Makes a data field
            $this->data['totalInc'] = $totalInc;
	}
        
        public function countRecipy(){
             $this->load->model('recipes');
            // gets a list of supplies
            $source = $this->recipes->all();
            $recipyCount=0;
            foreach ($source as $record)
            {
                $recipyCount += count($record);
            }
            $this->data['recipyCount'] =$recipyCount;
        }
        
        public function countProduct(){
            $this->load->model('stock');
            // gets a list of supplies
            $source = $this->stock->all();
            $productCount=0;
            foreach ($source as $record)
            {
                $productCount += count($record);
            }
            $this->data['productCount'] =$productCount;
        }

        
}