<?php

namespace App\Http\Controllers\Admin\UploadCSV;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Uploads;

class UploadOrchestraCSVController extends Controller
{
    protected $upload;
    /**
     * Create a new controller instance.
     * @author <Praveen>
     * @return void
     */
    public function __construct(Uploads $upload)
    {
      $this->middleware('auth');
      $this->upload = $upload;
    }

   /**
   * method to upload the the orchestra po and price csv files
   * @return [boolean] [success or fail]
   * @author Praveen <praveen@wizroots.com>
   */

  public function uploadOrchestraOrderCSVFile(Request $request)
  {

    request()->validate([
      'csv_files.*'  => 'required|max:102400',
    ]);

    $success = 0;

    if ($request->hasFile('csv_files')) {
      foreach ($request->csv_files as $csv_file) {

        $original_file_name = strtok($csv_file->getClientOriginalName(), " ");

        if (!preg_match('/ORC_M3_PO_FILE/', $original_file_name) && !preg_match('/ORC_M3_PRICE_FILE/', $original_file_name)) {

          return response()->json(['error' => true, 'message' => 'Invalid Files'], 422);
        }

        $success = $this->upload->bulkDumpOrchCsvData($csv_file, $original_file_name, $success);
      }
    }

    if ($success == 2) {
      $this->upload->validateCsvData();
      return response()->json(['error' => false, 'message' => 'CSV Files Uploaded Successfully'], 200);
    } else {
      return response()->json(['error' => true, 'message' => 'Please upload valid CSV files.'], 422);
    }
  }
}
