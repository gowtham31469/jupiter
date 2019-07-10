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
            // ['type'=>'PO Mngr','action' =>'All'],
            ['type'=>'PO Mngr','action' =>'PO Mngr Verified Pending'],
            ['type'=>'PO Mngr','action' =>'Verified'],
            ['type'=>'PO Mngr','action' =>'PO Mngr Rejected'],
            ['type'=>'PO Mngr','action' =>'PO Mngr Reapproved'],
            // ['type'=>'JC Mngr','action' =>'All'],
            ['type'=>'JC Mngr','action' =>'JC Pending'],
            ['type'=>'JC Mngr','action' =>'JC Created'],
            // ['type'=>'Order','action' =>'All'],
            ['type'=>'Order','action' =>'Dispacted'],
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
