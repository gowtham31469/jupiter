<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\OrchestraItemDetails;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use DB;
use Auth;
use App\RolePermissionMdl;

class Uploads
{
	/**
	 * method to insert the orchestra item details data into DB
	 * @param   $data 
	 * @return 
	 */
	public function insertOrchestraItemDetails($data)
	{
		OrchestraItemDetails::create([
	        'FactoryCode' 	  => $data->factorycode,
	        'SupplierName' 	  => $data->suppliername,
	        'SupplierAddress' => $data->supplieraddress,
	        'SupplierEmail' => $data->supplieremail,
	        'SupplierCountry' => $data->suppliercountry,
	        'BuyerEmail' => $data->buyeremail,
	        'First_ETD_SHIPMENT_DATE' => $data->first_etd_shipment_date,
	        'EditionNumber' => $data->editionnumber,
	        'VersionNumber' => $data->versionnumber,
	        'Collection' => $data->collection,
	        'BuyerPONo' => $data->buyerpono,
	        'BarcodeForPricesTags' => $data->barcodeforpricestags,
	        'UVC_TCO' => $data->uvc_tco,
	        'Description' => $data->description,
	        'Rayon' => $data->rayon,
	        'Color' => $data->color,
	        'Size' => $data->size,
	        'Stature' => $data->stature,
	        'Age' => $data->age,
	        'Theme' => $data->theme,
	        'Marque' => $data->marque,
	        'Disney' => $data->disney,
	        'Marvel' => $data->marvel,
	        'PommeFramboise' => $data->pommeframboise,
	        'PrixEurope' => $data->prixeurope,
	        'PrixClubEurope' => $data->prixclubeurope,
	        'PrixVenteKoweit' => $data->prixventekoweit,
	        'Prix_Vente_Arabie_Saoudite' => $data->prix_vente_arabie_saoudite,
	        'Prix_Club_Arabie_Saoudite' => $data->prix_club_arabie_saoudite,
	        'Prix_Vente_Canada' => $data->prix_vente_canada,
	        'Prix_Club_Canada' => $data->prix_club_canada,
	        'Prix_Vente_Suisse' => $data->prix_vente_suisse,
	        'Prix_Club_Suisse' => $data->prix_club_suisse,
	        'Prix_Vente_Chine' => $data->prix_vente_chine,
	        'Prix_Club_Chine' => $data->prix_club_chine,
	        'Prix_Vente_Turquie' => $data->prix_vente_turquie,
	        'Prix_Club_Turquie' => $data->prix_club_turquie,
	        'Prix_Vente_Maroc' => $data->prix_vente_maroc,
	        'Prix_Club_Maroc' => $data->prix_club_maroc,
	        'Prix_Vente_Ile_Maurice' => $data->prix_vente_ile_maurice,
	        'Prix_Club_Ile_Maurice' => $data->prix_club_ile_maurice,
	        'Prix_Vente_Emirats_Arabes_Unis' => $data->prix_vente_emirats_arabes_unis,
	        'Prix_Club_Emirats_Arabes_Unis' => $data->prix_club_emirats_arabes_unis,
	        'Prix_Vente_TUNISIE' => $data->prix_vente_tunisie,
	        'Prix_Club_TUNISIE' => $data->prix_club_tunisie,
	        'Prix_Vente_ALGERIE' => $data->prix_vente_algerie,
	        'Prix_Club_ALGERIE' => $data->prix_club_algerie,
	        'Quantity' => $data->quantity,
	        'UVC' => $data->uvc,
	        'ParcelBarCode' => $data->parcelbarcode,
	        'ParcelCode' => $data->parcelcode,
	        'HangTag_Print_Date' => $data->hangtag_print_date,
	        'Prix_Vente_QATAR' => $data->prix_vente_qatar,
	        'Prix_Club_QATAR' =>$data->prix_club_qatar,
	        'Style_Name' => $data->style_name,
	        'Prix_Vente_USA' => $data->prix_vente_usa,
	        'Prix_Club_USA' => $data->prix_club_usa,
	    ]);
	}

	/**
	 * method to update the orchestra item details in DB
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function updateOrchestraItemDetails($data)
	{
		OrchestraItemDetails::where('Collection',$data->collection)
			->where('UVC',$data->uvc)
			->where('BuyerPONo',$data->buyerpono)
			->where('VersionNumber',$data->versionnumber)
			->where('EditionNumber',$data->editionnumber)
			->where('Size',$data->size)
            ->update(['FactoryCode' => $data->factorycode,
	        'SupplierName' 	=> $data->suppliername,
	        'SupplierAddress' => $data->supplieraddress,
	        'SupplierEmail' => $data->supplieremail,
	        'SupplierCountry' => $data->suppliercountry,
	        'BuyerEmail' => $data->buyeremail,
	        'First_ETD_SHIPMENT_DATE' => $data->first_etd_shipment_date,
	        'BarcodeForPricesTags' => $data->barcodeforpricestags,
	        'UVC_TCO' => $data->uvc_tco,
	        'Description' => $data->description,
	        'Rayon' => $data->rayon,
	        'Color' => $data->color,
	        'Stature' => $data->stature,
	        'Age' => $data->age,
	        'Theme' => $data->theme,
	        'Marque' => $data->marque,
	        'Disney' => $data->disney,
	        'Marvel' => $data->marvel,
	        'PommeFramboise' => $data->pommeframboise,
	        'PrixEurope' => $data->prixeurope,
	        'PrixClubEurope' => $data->prixclubeurope,
	        'PrixVenteKoweit' => $data->prixventekoweit,
	        'Prix_Vente_Arabie_Saoudite' => $data->prix_vente_arabie_saoudite,
	        'Prix_Club_Arabie_Saoudite' => $data->prix_club_arabie_saoudite,
	        'Prix_Vente_Canada' => $data->prix_vente_canada,
	        'Prix_Club_Canada' => $data->prix_club_canada,
	        'Prix_Vente_Suisse' => $data->prix_vente_suisse,
	        'Prix_Club_Suisse' => $data->prix_club_suisse,
	        'Prix_Vente_Chine' => $data->prix_vente_chine,
	        'Prix_Club_Chine' => $data->prix_club_chine,
	        'Prix_Vente_Turquie' => $data->prix_vente_turquie,
	        'Prix_Club_Turquie' => $data->prix_club_turquie,
	        'Prix_Vente_Maroc' => $data->prix_vente_maroc,
	        'Prix_Club_Maroc' => $data->prix_club_maroc,
	        'Prix_Vente_Ile_Maurice' => $data->prix_vente_ile_maurice,
	        'Prix_Club_Ile_Maurice' => $data->prix_club_ile_maurice,
	        'Prix_Vente_Emirats_Arabes_Unis' => $data->prix_vente_emirats_arabes_unis,
	        'Prix_Club_Emirats_Arabes_Unis' => $data->prix_club_emirats_arabes_unis,
	        'Prix_Vente_TUNISIE' => $data->prix_vente_tunisie,
	        'Prix_Club_TUNISIE' => $data->prix_club_tunisie,
	        'Prix_Vente_ALGERIE' => $data->prix_vente_algerie,
	        'Prix_Club_ALGERIE' => $data->prix_club_algerie,
	        'Quantity' => $data->quantity,
	        'ParcelBarCode' => $data->parcelbarcode,
	        'ParcelCode' => $data->parcelcode,
	        'HangTag_Print_Date' => $data->hangtag_print_date,
	        'Prix_Vente_QATAR' => $data->prix_vente_qatar,
	        'Prix_Club_QATAR' =>$data->prix_club_qatar,
	        'Style_Name' => $data->style_name,
	        'Prix_Vente_USA' => $data->prix_vente_usa,
	        'Prix_Club_USA' => $data->prix_club_usa]);
	}

	public function insertOrchestraParcelDetails($data)
	{
		OrchestraParcelDetails::create([
            'FactoryCode' 	  => $data->factorycode,
            'SupplierName' 	  => $data->suppliername,
            'SupplierAddress' => $data->supplieraddress,
            'SupplierEmail' => $data->supplieremail,
            'SupplierCountry' => $data->suppliercountry,
            'EditionNumber' => $data->editionnumber,
            'VersionNumber' => $data->versionnumber,
            'BuyerPONo' => $data->buyerpono,
            'Theme' => $data->theme,
            'Quantity' => $data->quantity,
            'IsSTD' => $data->isstd,
            'UVC' => $data->uvc,
            'ParcelBarCode' => $data->parcelbarcode,
            'ParcelCode' => $data->parcelcode,
            'CatgDescr' => $data->catgdescr,
            'NomApple' => $data->nomapple,
   		]);
	}
	public function updateOrchestraParcelDetails($data)
	{
		OrchestraParcelDetails::where('UVC',$data->uvc)
			->where('BuyerPONo',$data->buyerpono)
			->where('VersionNumber',$data->versionnumber)
			->where('EditionNumber',$data->editionnumber)
			->where('IsSTD',$data->isstd)
            ->update(['FactoryCode' => $data->factorycode,
            'SupplierName' 	  => $data->suppliername,
            'SupplierAddress' => $data->supplieraddress,
            'SupplierEmail' => $data->supplieremail,
            'SupplierCountry' => $data->suppliercountry,
            'EditionNumber' => $data->editionnumber,
            'VersionNumber' => $data->versionnumber,
            'BuyerPONo' => $data->buyerpono,
            'Theme' => $data->theme,
            'Quantity' => $data->quantity,
            'IsSTD' => $data->isstd,
            'UVC' => $data->uvc,
            'ParcelBarCode' => $data->parcelbarcode,
            'ParcelCode' => $data->parcelcode,
            'CatgDescr' => $data->catgdescr,
            'NomApple' => $data->nomapple]);
	}

	public function insertOrchestraParcelQuantityDetails($data)
	{
		OrchestraParcelQuantityDetails::create([
            'FactoryCode' 	  => $data->factorycode,
            'EditionNumber' => $data->editionnumber,
            'VersionNumber' => $data->versionnumber,
            'BuyerPONo' => $data->buyerpono,
            'Theme' => $data->theme,
            'UVC_TCO' => $data->uvc_tco,
            'Quantity' => $data->quantity,
            'Color' => $data->color,
            'UVC' => $data->uvc,
            'Size' => $data->size,
            'BarcodeForPricesTags' => $data->barcodeforpricestags
   	 	]);
	}

	public function updateOrchestraParcelQuantityDetails($data)
	{
		OrchestraParcelQuantityDetails::where('UVC',$data->uvc)
			->where('BuyerPONo',$data->buyerpono)
			->where('VersionNumber',$data->versionnumber)
			->where('EditionNumber',$data->editionnumber)
			->where('Size',$data->size)
			->update(['FactoryCode' => $data->factorycode,
            'EditionNumber' => $data->editionnumber,
            'VersionNumber' => $data->versionnumber,
            'BuyerPONo' => $data->buyerpono,
            'Theme' => $data->theme,
            'UVC_TCO' => $data->uvc_tco,
            'Quantity' => $data->quantity,
            'Color' => $data->color,
            'UVC' => $data->uvc,
            'Size' => $data->size,
            'BarcodeForPricesTags' => $data->barcodeforpricestags]);
	}
}