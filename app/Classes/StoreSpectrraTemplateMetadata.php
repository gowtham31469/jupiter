<?php

namespace App\Classes;
use DB;
use App\TemplateBoardColorMapping;
use App\TemplateFrontColorsList;
use App\TemplateColorMatchMapping;

/**
 * Class to store spectrra prints templates metadata
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class StoreSpectrraTemplateMetadata
{
    public function storeTemplateData($request)
    {
        $board_text = DB::table('boards')->where('id', $request['board_name'])->value('name');
        $board_category_text = DB::table('board_category')->where('id', $request['board_category'])->value('name');

        $row_id = TemplateBoardColorMapping::updateOrCreate([
            'template_id' => $request['template_id_spectrra'],
            'buyer_template_table' => $request['buyer_spectrra'].'_template',
        ] ,  [
            'color_bar' =>  $request['color_bar'],
            'print_spec_bar' => $request['print_spec_bar'], 
            'tone_bar' => $request['tone_bar'], 
            'board_category_id' => $request['board_category'], 
            'board_category_text' => $board_category_text, 
            'board_id' => $request['board_name'], 
            'board_text' => $board_text, 
            'fsc_non_fsc' => $request['fsc_non_fsc'], 
            'gsm_value' => $request['gsm'], 
            'thickness' => $request['thickness'], 
            'board_size_length' => $request['board_size_length'], 
            'board_size_width' => $request['board_size_width'], 
            'print_size_length' => $request['print_size_length'], 
            'print_size_width' => $request['print_size_width'], 
            'design_size_length' => $request['design_size_length'], 
            'design_size_width' => $request['design_size_width'], 
            'cut_as' => $request['cut_as_1'].'/'.$request['cut_as_2'], 
            'front_color_count' => $request['front_color_count'], 
            'back_color_count' => $request['back_color_count'], 
            'back_color_text' => !is_null($request['back_color_1']) ? $request['back_color_1'] : '', 
            'repeats' => $request['repeats'], 
            'no_of_plates' => $request['no_of_plates'], 
            'cutting' => $request['cutting'], 
            'printing' => $request['printing'], 
            'coating' => $request['coating'], 
            'uv' => $request['uv'], 
            'lamination' => $request['lamination'], 
            'lamination_spec' => $request['lamination_spec'], 
            'pasting' => $request['pasting'], 
            'pasting_board' => $request['pasting_board'], 
            'die_cutting' => $request['die_cutting'], 
            'window_patch' => $request['window_patch'], 
            'window_patch_gsm' => $request['window_patch_gsm'], 
            'folder_gluer' => $request['folder_gluer'], 
            'embossing' => $request['embossing'], 
            'debossing' => $request['debossing'], 
            'perforation' => $request['perforation'], 
            'hole' => $request['hole'], 
            'hole_size' => $request['hole_size'], 
            'eyelet' => $request['eyelet'], 
            'eyelet_spec' => $request['eyelet_spec'], 
            'string_required' => $request['string_required'], 
            'string_spec' => $request['string_spec'], 
            'foil' => $request['foil'], 
        ]);

        $front_color_array = [];
        for ($i=0; $i < $request['front_color_count']; $i++) { 
            $front_color_array[] = array(
                'template_board_color_id' => $row_id->id,
                'front_color_text' => $request['front_color_'.($i+1)]
            );
        }

        TemplateFrontColorsList::where('template_board_color_id', $row_id->id)->delete();
        TemplateFrontColorsList::insert($front_color_array);

        $color_match_array = [];
        for ($i=0; $i < sizeof($request['color_match']); $i++) { 
            $color_match_array[] = array(
                'template_board_color_id' => $row_id->id,
                'color_match_text' => DB::table('color_match')->where('id', $request['color_match'][$i])->value('name')
            );
        }
        
        TemplateColorMatchMapping::where('template_board_color_id', $row_id->id)->delete();
        TemplateColorMatchMapping::insert($color_match_array);

        return 'success';
    }
}
