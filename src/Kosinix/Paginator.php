<?php
namespace Kosinix;

/*
 * Generic class for splitting large data into smaller chunks.
 */
class Paginator {

    /**
     * @var int Total rows of records
     */
    protected $total;

    /**
     * @var int
     */
    protected $current_page;

    /**
     * @var int Rows per page
     */
    protected $per_page;

    /**
     * @var int Always 1
     */
    protected $first_page;

    /**
     * @var int Maximum number of page based on $total and $per_page
     */
    protected $last_page;

    /**
     * @var int Always 0
     */
    protected $first_index;

    /**
     * @var int Last possible index
     */
    protected $last_index;

    /**
     * @var int Starting index of rows for current page
     */
    protected $start_index;

    /**
     * @var int Ending index of rows for current page
     */
    protected $end_index;

    /**
     * Paginator constructor.
     *
     * @param int $total Total number of rows to paginate
     * @param int $current_page Current page. Comes from web app.
     * @param int $per_page Rows per page
     */
    public function __construct($total, $current_page=1, $per_page=10){

        $this->resize($total, $current_page, $per_page);
    }

    /**
     * Re-compute everything and store values.
     *
     * @param int $total Total number of rows to paginate
     * @param int $current_page Current page. Comes from web app.
     * @param int $per_page Rows per page
     */
    public function resize($total, $current_page, $per_page){

        $current_page = ($current_page < 1) ? 1 : $current_page; // >= 1

        $per_page = ($per_page < 1) ? 1 : $per_page; // >= 1

        $this->start_index = ($current_page - 1) * $per_page;

        $this->end_index = $this->start_index + $per_page - 1;

        $this->first_index = 0;

        $this->last_index = $total - 1; // >= 0

        $this->first_page = 1;

        $this->last_page = ($total % $per_page) ? 1 : 0; //If division has remainder, add one page.
        $this->last_page = $this->last_page + floor($total / $per_page);

        $this->total = $total;

        $this->current_page = $current_page;

        $this->per_page = $per_page;

    }


    /**
     * Get Rows
     *
     * Slice a 1D array given offset start and per page
     *
     * @param $data
     * @param null $start
     * @param null $per_page
     *
     * @return array Sliced array of data.
     */
    public function getRows($data, $start=null, $per_page=null){
        if(null===$start){
            $start = $this->start_index;
        }
        if(null===$per_page){
            $per_page = $this->per_page;
        }

        return array_slice($data, $start, $per_page);
    }

    /**
     * Get $current_page - $range.
     *
     * @param int $range
     *
     * @return int
     */
    public function shortPageStart($range=3){
        $short = $this->current_page - $range;
        if($short<=0){
            $short = 1;
        }
        return $short;
    }

    /**
     * Get $current_page + $range.
     *
     * @param int $range
     *
     * @return int
     */
    public function shortPageEnd($range=3){
        $short = $this->current_page + $range;
        if($short > $this->last_page){
            $short = $this->last_page;
        }
        return $short;
    }

    /**
     * Total rows
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * Number of rows per page
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->per_page;
    }

    /**
     * First page. Always 1
     *
     * @return int
     */
    public function getFirstPage()
    {
        return $this->first_page;
    }

    /**
     * Last page
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->last_page;
    }

    /**
     * First index of all rows
     *
     * @return int
     */
    public function getFirstIndex()
    {
        return $this->first_index;
    }

    /**
     * Last index of all rows
     *
     * @return int
     */
    public function getLastIndex()
    {
        return $this->last_index;
    }

    /**
     * Start index of rows in the current page. Zero-based.
     *
     * @return int
     */
    public function getStartIndex()
    {
        return $this->start_index;
    }

    /**
     * End index of rows in the current page
     *
     * @return int
     */
    public function getEndIndex()
    {
        return $this->end_index;
    }

}