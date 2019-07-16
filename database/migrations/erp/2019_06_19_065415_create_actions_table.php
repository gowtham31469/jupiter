    <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('action');
            $table->timestamps();
        });


        $data = array(
            // ['type'=>'PO Manager','action' =>'All'],
            ['type'=>'PO Manager','action' =>'PO Manager Verification Pending'],
            ['type'=>'PO Manager','action' =>'Verified'],
            ['type'=>'PO Manager','action' =>'PO Manager Rejected'],
            ['type'=>'PO Manager','action' =>'PO Manager Reapproved'],
            // ['type'=>'JC Manager','action' =>'All'],
            ['type'=>'JC Manager','action' =>'JC Pending'],
            ['type'=>'JC Manager','action' =>'JC Created'],
            // ['type'=>'Order','action' =>'All'],
            ['type'=>'Order','action' =>'Dispatched'],
            ['type'=>'Order','action' =>'Pending For Dispatch'],
            ['type'=>'Order','action' =>'Partial Dispatch'],
            ['type'=>'Order','action' =>'Hold'],
            ['type'=>'Order','action' =>'Cancelled'],
            ['type'=>'Order','action' =>'In Production'],
            ['type'=>'Order','action' =>'Production Complete'],
            // ['type'=>'Invoice','action' =>'All'],
            ['type'=>'Invoice','action' =>'Within State'],
            ['type'=>'Invoice','action' =>'Inter State'],
            ['type'=>'Invoice','action' =>'GST'],
            ['type'=>'Invoice','action' =>'Job Works'],
            ['type'=>'Invoice','action' =>'Export']);

         DB::table('actions')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
