<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\{
    UserUpdateRequest,
    UserAddRequest
};
use Spatie\Permission\Models\Role;
use App;
use Illuminate\Support\Facades\Validator;
use App\Models\PDFFormMeta;
use App\Models\ClientInformation;
use DB;

class PDFFormController extends Controller {

    public function __construct() {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if (Auth::User()->id != 1) {
            return redirect('admin/dashboard')->with('error', 'You are not allowed to access this content');
        }

        return view('admin.pdfforms.index', ["firms" => 'sss', 'total' => '2']);
    }

    public function viewpdf($id) {
        if (Auth::User()->id != 1) {
            return redirect('admin/dashboard')->with('error', 'You are not allowed to access this content');
        }
        $form_name = base64_decode($id);
        return view('admin.pdfforms.viewpdf', compact('form_name'));
    }

    public function setupfields(Request $request, $id) {
        if (Auth::User()->id != 1) {
            return redirect('admin/dashboard')->with('error', 'You are not allowed to access this content');
        }
        if (isset($_REQUEST['v']) && $_REQUEST['v'] == 1) {
              return view('admin.pdfforms.setupfields_new', ["RequestedData" => $id, 'total' => '2']);
          
        } else {
            return view('admin.pdfforms.setupfields', ["RequestedData" => $id, 'total' => '2']);
        }
    }

    public function pdftest(Request $request, $id) {
#print_r($id);die;
        return view('admin.pdfforms.pdftest', ["RequestedData" => $id, 'total' => '2']);
    }

    public function pdftestnew(Request $request, $id) {
#print_r($id);die;
        return view('admin.pdfforms.pdftestnew', ["RequestedData" => $id, 'total' => '2']);
    }

    public function SaveAGroup(Request $request) {
        $_REQUEST['shortcode'] = ShortCode(time() . time() . time() . rand(0, 10000));
        DB::table('tila_pdfform_field_group')->insert($_REQUEST);

        die;
    }

    public function DeleteGroup(Request $request, $id) {
        DB::table('tila_pdfform_field_group')->where('ID', $id)->delete();
    }

    public function FieldMetaEntry(Request $request, $id) {

        return view('admin.pdfforms.pdfmeta', ["RequestedData" => $id, 'total' => '2']);
        die;
    }

    public function parentgroups(Request $request, $id) {
        $ParentList = DB::select('SELECT  * from tila_pdfform_field_group');
        $html = '<option value="0">Select Options</option>';
        foreach ($ParentList as $v) {
            $html .= '<option value="' . $v->ID . '">' . GetGroupNameWithParentByID($v->ID) . '</option>';
        }
        echo $html;
    }

    public function updateparentgroups(Request $request, $id) {
        $ParentList = DB::select('SELECT  n.ID,n.GroupName,n.IsThisGroupRepeat as chk,(select GroupName from tila_pdfform_field_group where ID=n.ParentGroupID) as ParentGroupID,n.shortcode  from tila_pdfform_field_group as n ORDER BY `ID` DESC');
        $html = '<tr><th>Group Name</th> <th>Parent Group Name</th> <th><center>Is Repeated</center></th> <th>ShortCode</th><th></th></tr>';
        foreach ($ParentList as $v) {
            $rpt = '<i class=" fa fa-close"></i>';
            if ($v->chk === 1) {
                $rpt = '<i class=" fa fa-check"></i>';
            }
            $p = '--No Parent--';
            $trash = '';
            if (isset($v->ParentGroupID)) {
                $p = $v->ParentGroupID;
                $trash = '<i data-delID="' . $v->ID . '" class="deletegroup fa fa-trash"></i>';
            }
            $html .= '<tr><td>' . GetGroupNameWithParentByID($v->ID) . '</td> <td>' . $p . '</td> <td><center>' . $rpt . '</center></td> <td><input id="' . $v->shortcode . '" type="text" readonly="true" value="' . ShortCode($v->shortcode, true) . '"><button class="btn btn-danger btn-sm" type="button" data-clipboard-demo="" data-clipboard-action="copy" data-clipboard-text="' . ShortCode($v->shortcode, true) . '"><i  class=" fa fa-clipboard"></i></button></td><td>' . $trash . '</td></tr>';
        }
        echo $html;
        die;
    }

    public function PDFList(Request $request, $id) {
        echo $data = (base64_decode($id));
        $data = array_map('base64_decode', explode(',', $data));
        $Fpdf = DB::select('SELECT  * from tila_pdfform_list');
        
        $isone = 0;
        $isz = 0;
        foreach ($Fpdf as $v) {
            if ($v->Syn == 0) {
                $isz++;
            } else {
                $isone++;
            }
            if (count($Fpdf) == $isone) {
                $ff = md5($v->FileName);
                $i = array('Syn' => 0);
                DB::table('tila_pdfform_list')->where('FileNameEncripted', $ff)->update($i);
            }
        }
        
        foreach ($data as $v) {
            $Files = DB::select('SELECT  * from tila_pdfform_list where FileNameEncripted="' . md5($v) . '"');
            if (count($Files) == 0) {
                if (strlen($v) > 3) {
                    $i['FileName'] = $v;
                    $i['FileNameEncripted'] = md5($v);
                    DB::table('tila_pdfform_list')->insert($i);
                }
            }
        }
        die;
    }

    public function AllPdfFiles() {
        $PATH = storage_path('app/forms/all');
        $dirPath = scandir($PATH);

//pre($dirPath);
        unset($dirPath[array_search(".", $dirPath)]);
        unset($dirPath[array_search("..", $dirPath)]);
        $dPath = array_map('base64_encode', $dirPath);
        $datas = base64_encode(implode(',', $dPath));
        $Data = array();
        foreach ($dirPath as $k => $v) {
            $Data[$k]['FileName'] = explode('.', $v)[0];
            $Data[$k]['FileLink'] = '/admin/pdfform/setupfields/' . base64_encode($v);
            $Data[$k]['Fileicon'] = '/Pdficon.png';
        }

        echo json_encode($Data);
// /admin/pdfform/setupfields/<?php echo base64_encode($v); 
    }

    public function IsmasterField(Request $request, $id) {
        $Fpdf = DB::select('SELECT  * from tila_pdfform_meta where FieldUniqueID="' . $id . '"');
        $i = 0;
        if ($Fpdf[0]->isMaterField == 0) {
            $i = 1;
            DB::table('tila_pdfform_field_relationship')->where('ChildFieldUniqueID', $id)->delete();
        }
        $i = array('isMaterField' => $i);
        DB::table('tila_pdfform_meta')->where('FieldUniqueID', $id)->update($i);
        $Fpdf = DB::select('SELECT  * from tila_pdfform_meta where FieldUniqueID="' . $id . '"');
        echo json_encode($Fpdf[0]->isMaterField);
    }

    

    public function FieldNameUpdate(Request $request) {
        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $i = array('CustomFieldName' => $_REQUEST['n'], 'CustomFieldNameFront' => $_REQUEST['fn']);
            if (isset($_REQUEST['group']) && count($_REQUEST['group']) > 0) {
                DB::table('tila_pdfform_field_group_setFields')->where('FieldUniqueID', $id)->delete();
                foreach ($_REQUEST['group'] as $v) {

                    $j['FieldUniqueID'] = $id;
                    $j['FieldGroupID'] = $v;
                    DB::table('tila_pdfform_field_group_setFields')->insert($j);
                }
            } else {

                DB::table('tila_pdfform_field_group_setFields')->where('FieldUniqueID', $id)->delete();
            }
            if (isset($_REQUEST['rel'])) {
                DB::table('tila_pdfform_field_relationship')->where('ChildFieldUniqueID', $id)->delete();
                $k['ParentFieldUniqueID'] = $_REQUEST['rel'];
                $k['ChildFieldUniqueID'] = $id;
                DB::table('tila_pdfform_field_relationship')->insert($k);
            }

            DB::table('tila_pdfform_meta')->where('FieldUniqueID', $id)->update($i);
        }
    }

    public function autoCompletedata() {
        echo AutoCompleteData();
    }

    public function PDFMasterCreate(Request $request) {

        return view('admin.pdfforms.pdfmaster');
    }

}
