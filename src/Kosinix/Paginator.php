<?php
/**
 * Paginator - Split large data into smaller chunks.
 * 
 * @author Nico Amarilla
 */
namespace Kosinix;

class Paginator {
    
    /** @var int Total number of records */
    protected $total;
    
    /** @var int Current page */
    protected $current_page;
    
    /** @var int Number of records to show per page */
    protected $per_page;
    
    /** @var int Starting paginator page */
    protected $starting_page;
    
    /** @var int Ending paginator page */
    protected $ending_page;
    
    /** @var int Maximum number of page based on $total and $per_page */
    protected $last_page;
    
    /** @var int Page before current page */
    protected $previous_page;
    
    /** @var int Page after current page */
    protected $next_page; 
    
    /** @var int Starting index of records for current page */
    protected $start_index;
    
    /** @var int Ending index of records for current page */
    protected $end_index;
    
    /** @var int Number of pages to show on left and right of current page */
    protected $pages_width;
    
    /**
    * Constructor
    *
    * @param int $total Total number of records
    * @param int $current_page Current page to show
    * @param int $per_page Number of records per page
    * @param int $pages_width Number of pages to show on left and right of current page.
    */
    public function __construct($total, $current_page=1, $per_page=10, $pages_width=null){
        
        $this->compute_values($total, $current_page, $per_page, $pages_width);
    }
    
    /**
    * Compute Values
    *
    * Compute and save values based on passed parameters. You can use this instead of the constructor to re-compute the values.
    *
    * @param int $total Total number of records
    * @param int $current_page Current page to show
    * @param int $per_page Number of records per page
    * @param int $pages_width Number of pages to show on left and right of current page.
    */
    public function compute_values($total, $current_page=1, $per_page=10, $pages_width=null){
        $this->total = (int) $total;
        $this->per_page = (int) $per_page;
        $this->last_page = $this->compute_last_page($total, $per_page);
        $this->current_page = $this->compute_current_page( $current_page, $this->last_page );
        $this->previous_page = $this->compute_previous_page($current_page);
        $this->next_page = $this->compute_next_page($current_page, $this->last_page);
        $this->starting_page = $this->compute_starting_page($pages_width, $current_page);
        $this->ending_page = $this->compute_ending_page($pages_width, $current_page, $this->last_page);
        $this->start_index = $this->compute_start_index($current_page, $per_page, $this->last_page);
        $this->end_index = $this->compute_end_index($total, $this->start_index, $per_page);
        $this->pages_width = $pages_width;
    }
    
    /**
    * Compute Last Page
    *
    * Get the last page based on $total and $per_page
    *
    * @param int $total Total number of records
    * @param int $per_page Number of records per page
    * @return int Last page
    */
    public function compute_last_page($total, $per_page){
        if( $total == 0 ){
            return 1;
        }
        $remainder = ($total % $per_page) ? 1 : 0; //If division has remainder, add one more page.
        return (int) (floor($total / $per_page) + $remainder);
    }
    
    /**
    * Compute Current Page
    *
    * Bounds check for current page
    *
    * @param int $current_page Current page
    * @param int $last_page Last page 
    * @return int Current page.
    */
    public function compute_current_page( $current_page, $last_page ){
        if($current_page > $last_page){ // Do some bounds check
            $current_page = $last_page; // Snap to last page if current page is greater
        }
        return $current_page;
    }
    
    /**
    * Compute Previous Page
    *
    * Get page before current page
    *
    * @param int $current_page Current page
    * @return int Previous page.
    */
    public function compute_previous_page($current_page){
        $previous_page = $current_page - 1;
        
        return (int) $previous_page;
    }
    
    /**
    * Compute Next Page
    *
    * Get page after current page
    *
    * @param int $current_page Current page
    * @return int Next page.
    */
    public function compute_next_page($current_page, $last_page){
        $next_page = $current_page + 1;
        
        return (int) $next_page;
    }
    
    /**
    * Compute Starting Page
    *
    * Get starting page. Can be equal or greater than 1 depending on $pages_width
    *
    * @param int $pages_width Left and right pages of current page
    * @param int $current_page Current page
    * @return int Starting page.
    */
    public function compute_starting_page($pages_width, $current_page){
        if(null===$pages_width){
            return 1;
        }
        $starting_page = $current_page - $pages_width;
        if($starting_page < 1){
            $starting_page = 1; // Bounds check
        }
        return (int) $starting_page;
    }
    
    /**
    * Compute Ending Page
    *
    * Get ending page. Can be equal to or less than $last_page depending on $pages_width
    *
    * @param int $pages_width Left and right pages of current page
    * @param int $current_page Current page
    * @param int $last_page Last page
    * @return int Ending page.
    */
    public function compute_ending_page($pages_width, $current_page, $last_page){
        if(null===$pages_width){
            return $last_page;
        }
        $ending_page = $current_page + $pages_width;
        if($ending_page > $last_page){
            $ending_page = $last_page;
        }
        return (int) $ending_page;
    }
    
    /**
    * Compute Start Index
    *
    * Get the first index of records in the current page
    * 
    * @param int $current_page Current page. Should be a positive integer
    * @param int $per_page Number of records per page
    * @param int $last_page Maximum page
    * @return int Starting record index.
    */
    public function compute_start_index($current_page, $per_page, $last_page) {
        if( $current_page > $last_page ){ // Check and fix overflow
            $current_page = $last_page;
        }
        if( $current_page <= 0 ) { // Check and fix for negative integers
            $current_page = 1;
        }
        
        return (int) (($current_page - 1) * $per_page);
    }
    
    /**
    * Compute End Index
    *
    * Get the ending index of records in the current page
    * 
    * @param int $total Total records
    * @param int $start_index Starting index of records in current page
    * @param int $per_page Number of records per page
    * @return int Ending record index.
    */
    public function compute_end_index($total, $start_index, $per_page) {
        if($total <= 0 ){ // Nothing to do if total is zero
            return 0;
        }
        $end = ($start_index + $per_page)-1;
        if( $end >= $total ){ // Check and fix overflow
            $end = $total-1;
        }
        return (int) $end;
    }
    
    
    /**
    * Get Rows
    *
    * Slice a 1D (one dimensional) array given offset start and per page
    *
    * @param array $data One dimesional array of records
    * @param int|null $start Starting index
    * @param int|null $per_page Number of records per page
    * @return array Sliced array of data.
    */
    public function get_rows($data, $start=null, $per_page=null){
        if(null===$start){
            $start = $this->start_index;
        }
        if(null===$per_page){
            $per_page = $this->per_page;
        }
		
		return array_slice($data, $start, $per_page);
    }
    
    
    /**
    * Getters
    */
    public function get_total(){
        return $this->total;
    }
    
    public function get_current_page(){
        return $this->current_page;
    }
    
    public function get_per_page(){
        return $this->per_page;
    }
    
    public function get_starting_page(){
        return $this->starting_page;
    }
    
    public function get_ending_page(){
        return $this->ending_page;
    }
    
    public function get_last_page(){
        return $this->last_page;
    }
    
    public function get_previous_page(){
        return $this->previous_page;
    }
    
    public function get_next_page(){
        return $this->next_page;
    }
    
    public function get_start_index(){
        return $this->start_index;
    }
    
    public function get_end_index(){
        return $this->end_index;
    }
    
    public function get_pages_width(){
        return $this->pages_width;
    }
}