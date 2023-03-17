<?php

namespace App\Classes;

use DB;
use Carbon\Carbon;

/**
 * Common class for orchestra tree structure
 * @author [Gowtham] <[gowtham@wizroots.com]>
 */
class OrchestraTreeStructure
{

    private $current_date;
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function __construct()
    {
        // get the current time
        $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
    }

    public function treeViewHT()
    {
        $treeviewheat_transfer= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','62')->orWhere('id','63')->orWhere('tag_name','heat_transfer')->orderBy('item_name', 'asc')->get()->toArray();
        if($treeviewheat_transfer){
            $childs = array();
            foreach($treeviewheat_transfer as &$item) 
            {
            $childs[$item->parent_id][] = &$item;
            unset($item);
            }
            foreach($treeviewheat_transfer as &$item) 
            {
                if (isset($childs[$item->id]))
                {
                    $item->children = $childs[$item->id];
                    unset($item);
                }
            }
            $tree = $childs[0];
            return $tree;

        }
    }

    public function treeViewSpecial()
    {
        $treeviewspecial= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('id','104')->orWhere('id','105')->orWhere('tag_name','special')->orderBy('item_name', 'asc')->get()->toArray();
        if($treeviewspecial){
            $childs = array();
            foreach($treeviewspecial as &$item) 
            {
            $childs[$item->parent_id][] = &$item;
            unset($item);
            }
            foreach($treeviewspecial as &$item) 
            {
                if (isset($childs[$item->id]))
                {
                    $item->children = $childs[$item->id];
                    unset($item);
                }
            }
            $tree = $childs[0];
            return $tree;

        }
    }

    public function treeViewMain()
    {
        $treeviewmain_label= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','2')->orWhere('id','10')->orWhere('id','13')->orWhere('tag_name','main')->orWhere('tag_name','MS')->orWhere('tag_name','main_size')->orderBy('item_name', 'asc')->get()->toArray();
        if($treeviewmain_label){
            $childs = array();
            foreach($treeviewmain_label as &$item) 
            {
            $childs[$item->parent_id][] = &$item;
            unset($item);
            }
            foreach($treeviewmain_label as &$item) 
            {
                if (isset($childs[$item->id]))
                {
                    $item->children = $childs[$item->id];
                    unset($item);
                }
            }
            $tree = $childs[0];
            return $tree;

        }
    }

    public function treeViewSize()
    {
        $treeviewsize_label= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','2')->orWhere('id','10')->orWhere('id','13')->orWhere('tag_name','size')->orWhere('tag_name','MS')->orWhere('tag_name','main_size')->orderBy('item_name', 'asc')->get()->toArray();
        if($treeviewsize_label){
            $childs = array();
            foreach($treeviewsize_label as &$item) 
            {
            $childs[$item->parent_id][] = &$item;
            unset($item);
            }
            foreach($treeviewsize_label as &$item) 
            {
                if (isset($childs[$item->id]))
                {
                    $item->children = $childs[$item->id];
                    unset($item);
                }
            }
            $tree = $childs[0];
            return $tree;

        }
    }


}