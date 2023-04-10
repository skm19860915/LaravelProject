<?php
if (!function_exists('FieldType')) {

    function FieldType($d) {
        $type = 0;
        switch ($d) {
            case "Button":
                $type = 1;
                break;
            case "CheckBox":
                $type = 2;
                break;
            case "RadioButton":
                $type = 3;
                break;
            case "Textbox":
                $type = 4;
                break;
            case "MultiChoice":
                $type = 5;
                break;
            case "Signature":
                $type = 6;
                break;
            default:
                break;
        }
        return $type;
    }

}
if (!function_exists('GetGroupNameWithParentByID')) {

    function GetGroupNameWithParentByID($id, $data = '') {
        $v = DB::select('select GroupName,ParentGroupID from tila_pdfform_field_group  where ID=' . $id);
        if ($v[0]->ParentGroupID != 0) {
            $data .= GetGroupNameWithParentByID($v[0]->ParentGroupID, $data);
        }
        $data .= ':' . $v[0]->GroupName;

        return $data;
    }

}
if (!function_exists('GetLabelStep')) {

    function GetLabelStep($d, $i = 0) {
        $Grp = DB::table('tila_pdfform_field_group')->where("ID", $d)->first();
        if ($Grp->ParentGroupID > 0) {
            $i++;
            $i = GetLabelStep($Grp->ParentGroupID, $i);
        }
        return $i;
    }

}

if (!function_exists('GetFieldValue')) {

    function GetFieldValue($key = '', $uID = 0) {

        $Grp = DB::table('tila_pdfform_metaField_SaveData')->where("UserID", $uID)->where("FieldUniqueID", $key)->first();

        return json_decode(json_encode($Grp), true)['Value'];

//return $Grp['Value'];
    }

}

if (!function_exists('GetTabTitle')) {

    function GetTabTitle($Field) {
        extract($Field);
        $Grp = DB::table('tila_pdfform_field_group')->where("ParentGroupID", $ID)->get();

//
        foreach ($Grp as $v) {
//echo $v->ID;
            $Grp1 = DB::table('tila_pdfform_field_group')->where("ParentGroupID", $v->ID)->get();
            if (count($Grp1) > 0) {
                $tabsID = md5($v->GroupName . GetLabelStep($v->ID));
                echo '<li  data-tab="' . $tabsID . '">' . $v->GroupName . '</li>';
            }
        }
    }

}
if (!function_exists('FieldType')) {

    function FieldType($d) {
        $type = 0;
        switch ($d) {
            case "Button":
                $type = 1;
                break;
            case "CheckBox":
                $type = 2;
                break;
            case "RadioButton":
                $type = 3;
                break;
            case "Textbox":
                $type = 4;
                break;
            case "MultiChoice":
                $type = 5;
                break;
            case "Signature":
                $type = 6;
                break;
            default:
                break;
        }
        return $type;
    }

}

if (!function_exists('GetFieldType')) {

    function GetFieldType($d) {
        $type = 0;
        switch ($d) {
            case 1:
                $type = "Button";
                break;
            case 2:
                $type = "CheckBox";
                break;
            case 3:
                $type = "RadioButton";
                break;
            case 4:
                $type = "Textbox";
                break;
            case 5:
                $type = "SelectBox";
                break;
            case 6:
                $type = "Signature";
                break;
            default:
                break;
        }
        return $type;
    }

}





if (!function_exists('ShortCode')) {

    function ShortCode($ID, $flag = false) {
        if ($flag == false) {
            return substr(md5($ID), 0, 10);
        } else {
            return $ID;
        }
    }

}





if (!function_exists('UpFileName')) {

    function UpFileName($f) {
        return md5($f);
    }

}


if (!function_exists('chkNameShow')) {

    function chkNameShow($f = '', $i = 5) {
        $fieldNameg = explode('_', $f);
        if (isset($fieldNameg[$i])) {
            return $fieldNameg[$i];
        } else {
            return '';
        }
    }

}

if (!function_exists('CallPDFDataBYGroup')) {

    function CallPDFDataBYGroup($data) {
//$ParentList = DB::select('SELECT  * from tila_pdfform_field_group');
        $Grp = DB::table('tila_pdfform_field_group')->where("shortcode", $data['ShortCode'])->first();
        $Field['ID'] = $Grp->ID;
        $Field['UID'] = $data['UserID'];
        $Field['title'] = 1;
        $Field['arrflag'] = $Grp->IsThisGroupRepeat;
        $data['radioflag'] = $Grp->IsThisGroupRadio;
        $Field['label'] = 0;
        $Field['tabsID'] = 0;
        $Field['CAID'] = $data['CaseID'];
        echo '<ul class="tabbingBox TabbingBoxShows">';
//GetMetaFields($Field);
        GetTabTitle($Field);
        echo '</ul>';
        $Field['title'] = 0;
        GetMetaFields($Field);
    }

}

if (!function_exists('GetMetaFields')) {

    function GetMetaFields($Field) {
        $data = $Field;

        extract($Field);

        $ChkIsFirmQ = "select role_id,firm_id from users where id='" . $UID . "'";
        $ChkIsFirmR = DB::select($ChkIsFirmQ);
        $q = "";
#pre($ChkIsFirmR);die;
        if ($ChkIsFirmR[0]->role_id == 4) {
            $q = "SELECT file_type FROM client_information_forms as c WHERE c.firm_id = '" . $ChkIsFirmR[0]->firm_id . "' group by file_type";
        } else {
            $q = "SELECT file_type FROM client_information_forms as c WHERE c.case_id = '" . $CAID . "' and client_id='" . $UID . "'";
        }
//echo $q;

        $v = DB::select($q);

        $file = array();
        foreach ($v as $vv) {
            $file[] = $vv->file_type;
        }

        $ND = array_map('UpFileName', array_map('strtolower', json_decode(json_encode($file))));
        $Grp = DB::table('tila_pdfform_field_group')->where("ID", $ID)->first();
        $q = 'select m.FieldUniqueID,m.fieldtype,m.FieldName,m.CustomFieldNameFront,m.pdffileEncripted from tila_pdfform_field_group_setFields as gf,tila_pdfform_meta as m where m.FieldUniqueID=gf.FieldUniqueID and gf.FieldGroupID=' . $ID;

        $v = DB::select($q);
        if (Count($v) > 0) {
            if (GetLabelStep($ID) == 1) {
                $tabsID = md5($Grp->GroupName . GetLabelStep($ID));
            }
            if ($Grp->ParentGroupID > 0 && GetLabelStep($ID) > 1 && $title == 0) {
                echo '<h4 class="TabeBox ' . $tabsID . '">' . $Grp->GroupName . '</h4>';
            }

            if ($title == 0 && GetLabelStep($ID) > 1) {

                echo '<div class="TabeBox ' . $tabsID . '" >';
                if ($arrflag == 1) {
                    echo '<div class="arrayGroupings"><div class="FieldArrayrContent"></div><div class="arrayGroups"><div class="gpar">';
                }
                if ($radioflag == 1) {
                    echo '<div class="RadioGroupings"><div class="FieldRadioContent"></div><div class="RadioGroups"><div class="rgpar">';
                }
                foreach ($v as $f) {
                    $value = GetFieldValue($f->FieldUniqueID, $UID);
//                    echo '<br>FieldUniqueID:' . $f->FieldUniqueID;
//                    echo '<br>UID:' . $UID;
//                    echo '<br>Is Array:' . $arrflag;
//                    echo '<br>IsValue:' . $value;
                    if (in_array($f->pdffileEncripted, $ND)) {
                        ?>
                        <div>
                            <label class="fullwidthfields">
                                <?php echo $f->CustomFieldNameFront; ?>

                                <?php
                                $dataField['type'] = $f->fieldtype;
                                $dataField['UniqueKey'] = $f->FieldUniqueID;
                                $dataField['arrflag'] = $arrflag;
                                $dataField['rdoflag'] = $radioflag;
                                $dataField['value'] = $value;
                                //pre($dataField);

                                echo ByTypeCreateField($dataField);
                                ?>
                            </label>
                        </div>
                        <?php
                    }
                }
                if ($arrflag == 1) {
                    echo '</div><i class="btn btn-success FieldArrayr fa fa-plus"></i><i style="display:none;" class="btn btn-danger FieldArrayremove fa fa-trash"></i></div></div>';
                }
                if ($radioflag == 1) {
                    echo '</div></div></div>';
                }
                echo '</div>';
            }
        }
        $GrpChile = DB::table('tila_pdfform_field_group')->where("ParentGroupID", $ID)->get();
        if (count($GrpChile) > 0) {
            foreach ($GrpChile as $g) {
                $label = GetLabelStep($g->ID);
                $data['ID'] = $g->ID;
                $data['arrflag'] = $g->IsThisGroupRepeat;
                $data['radioflag'] = $g->IsThisGroupRadio;
                $data['tabsID'] = $tabsID;
                $data['label'] = $label;
                GetMetaFields($data);
            }
        }
    }

}

//UniQue Field ID
if (!function_exists('GetFieldKey')) {

    function GetFieldKey($file, $fieldName) {
        $file = base64_encode($file);
        return md5($file . $fieldName . $file);
    }

}


if (!function_exists('GetFieldValueForForm')) {

    function GetFieldValueForForm($UID, $FILENAME) {

        $FILENAME = md5($FILENAME);
        $q = "select m.* from tila_pdfform_meta as m where m.pdffileEncripted='" . $FILENAME . "'";

        $v = DB::select($q);

        
        foreach ($v as $k => $vv) {
            $pdfindex = '';
            if(!empty($v[$k]->pdfindex)) {
                $pdfindex = $v[$k]->pdfindex;
            }
            $v[$k]->name = $v[$k]->FieldID;
            // $v[$k]->value = $pdfindex;
            $v[$k]->value = GetFieldValueByMasterFieldValue($v[$k]->FieldUniqueID, $UID);
            unset($v[$k]->ID);
            unset($v[$k]->fieldtype);
            unset($v[$k]->FieldUniqueID);
            unset($v[$k]->FieldID);
            unset($v[$k]->FieldName);
            unset($v[$k]->pdffile);
            unset($v[$k]->pdffileEncripted);
            unset($v[$k]->isMaterField);
            unset($v[$k]->CustomFieldName);
            unset($v[$k]->CustomFieldNameFront);
        }

        return json_encode($v);
    }

}

if (!function_exists('GetFieldValueByMasterFieldValue')) {

    function GetFieldValueByMasterFieldValue($fieldID, $UID) {
        $fieldID = GetAndChkMasterFieldID($fieldID);
        $q = "select Value as vdata from tila_pdfform_metaField_SaveData where FieldUniqueID='" . $fieldID . "' and UserID='" . $UID . "'";
        $v = DB::select($q);
        if (count($v) > 0)
            return $v[0]->vdata;
    }

}
if (!function_exists('GetAndChkMasterFieldID')) {

    function GetAndChkMasterFieldID($fieldID) {
        $q = "select isMaterField from tila_pdfform_meta where FieldUniqueID='" . $fieldID . "'";
        $v = DB::select($q);
        if ($v[0]->isMaterField == 1)
            return $fieldID;
        else {
            $q = "select ParentFieldUniqueID from tila_pdfform_field_relationship where ChildFieldUniqueID='" . $fieldID . "'";
            $v = DB::select($q);
            if (count($v) > 0) {
                return $v[0]->ParentFieldUniqueID;
            } else {
                return $fieldID;
            }
        }
    }

}




if (!function_exists('updatePdfFormAllField')) {

    function updatePdfFormAllField($ID, $Data) {
        $file = explode('/', $_REQUEST['file']);

        $fileName = $file[count($file) - 1];
        $a = (json_decode($_REQUEST['data']));
        $newData = array();
        $uc = DB::table("client_information_forms")->where("id", $ID)->first();

        foreach ($a as $k => $v) {
            $newData[$k]['FieldUniqueID'] = $a[$k]->name = GetFieldKey($uc->file_type, $v->name);
            $newData[$k]['Value'] = $a[$k]->value;
            $newData[$k]['UserID'] = $uc->client_id;
            SavePDFDataHelper($newData[$k]['FieldUniqueID'], $newData[$k]['Value'], $newData[$k]['UserID']);
        }
    }

}


if (!function_exists('SavePDFDataHelper')) {

    function SavePDFDataHelper($key, $Value, $UID) {

        //if (strlen(trim($Value)) > 1) {
        if (strlen($Value) > 1) {

            /* ------------------User And Client Table Update Static Code---------- */
            UpDateClientDataInNewClientTable($key, $Value, $UID);
            /* ------------------User And Client Table Update Static Code---------- */
            UpdateAllRelations($key, $UID, $Value);

//UpdatePDFMeta($key, $UID, $Value);
        }
    }

}
if (!function_exists('UpDateClientDataInNewClientTable')) {

    function UpDateClientDataInNewClientTable($key, $Value, $UID) {
        $a = array('8bf50681e9b287dfa0583a3562fe845b', '2dd512658a2dcd4ef82002948e519883', '0ae4d26579604dd2e6da6afc65353149');
        // if (in_array($key, $a)) {
        //     if ($key == '8bf50681e9b287dfa0583a3562fe845b') {
        //         $l['first_name'] = $Value;
        //         DB::table('new_client')->where('user_id', $UID)->update($l);
        //     }
        //     if ($key == '2dd512658a2dcd4ef82002948e519883') {
        //         $l['last_name'] = $Value;
        //         DB::table('new_client')->where('user_id', $UID)->update($l);
        //     }
        //     if ($key == '0ae4d26579604dd2e6da6afc65353149') {
        //         $l['middle_name'] = $Value;
        //         DB::table('new_client')->where('user_id', $UID)->update($l);
        //     }
        //     if ($key == 'a480d9294c484e7d7f176d2d32ed69d2') {
        //         $l['cell_phone'] = $Value;
        //         DB::table('new_client')->where('user_id', $UID)->update($l);
        //     }
        //     $v = DB::table('new_client')->where('user_id', $UID)->first();
        //     $e = '';
        //     $e .= " " . $v->first_name;
        //     $e .= " " . $v->middle_name;
        //     $e .= " " . $v->last_name;
        //     $e = trim($e);
        //     $k['name'] = $e;
        //     DB::table('users')->where('id', $UID)->update($k);
        // }
    }

}
if (!function_exists('UpdateAllRelations')) {

    function UpdateAllRelations($Ukey, $UserID, $Value) {
        $Value = trim($Value);
        $q = "SELECT * FROM tila_pdfform_field_relationship WHERE  (ChildFieldUniqueID LIKE '%" . $Ukey . "%')";
        $v = DB::select($q);
        if (count($v) > 0) {
            foreach ($v as $vv) {
                UpdatePDFMeta($vv->ParentFieldUniqueID, $UserID, $Value);
            }
        } else {
            UpdatePDFMeta($Ukey, $UserID, $Value);
        }
    }

}

if (!function_exists('UpdatePDFMeta')) {

    function UpdatePDFMeta($key, $UID, $Value) {

        $v = DB::table('tila_pdfform_metaField_SaveData')->where('FieldUniqueID', $key)->where('UserID', $UID)->count();

        $j['FieldUniqueID'] = $key;
        $j['UserID'] = $UID;
        $j['Value'] = $Value;
#pre($j);echo $v;die;
        if ($v == 0) {
            DB::table('tila_pdfform_metaField_SaveData')->insert($j);
        } else {
            DB::table('tila_pdfform_metaField_SaveData')->where('FieldUniqueID', $key)->where('UserID', $UID)->update($j);
        }
    }

}

if (!function_exists('AutoCompleteData')) {

    function AutoCompleteData($table = '', $where = "") {
        $uc = DB::table("tila_AutoCompleteData")->get();
        $a = array();
#pre($uc);
        foreach ($uc as $v) {
            $a[] = base64_decode($v->Data);
//DB::table('tila_AutoCompleteData')->insert($newData);
        }

//$code=array_map('base64_encode', $a);
        $d = (array_values(array_filter(array_unique($a))));
        echo json_encode($d);
    }

}

if (!function_exists('ByTypeCreateField')) {

    function ByTypeCreateField($dataField = array()) {
//                                $dataField['type']=$f->fieldtype;
//                                $dataField['UniqueKey']=$f->FieldUniqueID;
//                                $dataField['arrflag']=$arrflag;
//                                $dataField['rdoflag']=$arrflag;
//                                $dataField['value']=$value;
        $d = $dataField['type'];
        $name = $dataField['UniqueKey'];
        $flag = $dataField['arrflag'];
        $val = $dataField['value'];
        $rdo = $dataField['rdoflag'];



        $arr = '';
        if ($flag == 1) {
            $arr = '[]';
        }
        $type = 0;
        switch ($d) {
            case 1:
                $type = "<input type='button' name='" . $name . $arr . "'>";
                break;
            case 2:
                $chk = '';
                $class = "";
                $type = 'checkBox';
                if ($val == 'on') {
                    $chk = "checked='checked'";
                }
                if ($rdo == 1) {
                    $type = 'radio';
                    $class = "radiobtns";
                }
                $type = "<input class='" . $class . "' " . $chk . " type='" . $type . "' name='" . $name . $arr . "'>";
                break;
            case 3:
                $type = "<input type='radio' name='" . $name . $arr . "'>";
                break;
            case 4:
                $type = "<input value='" . $val . "' type='text' name='" . $name . $arr . "'>";
                break;
            case 5:
                $type = "<select name='" . $name . $arr . "'></select>";
                break;
            case 6:
                $type = "<input value='" . $val . "' type='text' name='" . $name . $arr . "'>";
                break;
            default:
                break;
        }
        return $type;
    }

}
/* ----------------------------GetData By Client In PDF Forms---------------- */
if (!function_exists('GetDataByClientInPDFForms')) {

    function GetDataByClientInPDFForms($CID = 0) {

        $v = DB::table('tila_pdfform_field_group')->where('ParentGroupID', 61)->get();
// $q = "SELECT * FROM `tila_pdfform_field_group` WHERE `ParentGroupID` = '61'";
        foreach ($v as $vv) {
//pre($vv);
//pre(getallchild($vv->ID)); 
            ?> 
            <div data-shortcode="<?php echo $vv->shortcode ?>" data-clientID="<?php echo $CID; ?>"  class="TabBoxByPDF general-info-repeat">
                <div class="general-info-head">
                    <h3><?php echo $vv->GroupName; ?> Information</h3>
                    <p>General Information General Information</p>
                    <p>General Information General Information</p>
                </div>
                <div class="general-info-percent">
                    <?php
                    if (count(getallchild($vv->ID)) == 0) {
                        echo '0';
                    } else {
                        echo '100';
                    }
                    ?>%</div>
                <div class="general-info-edit">
                    <a href="#">Edit</a>
                </div>
            </div>
            <?php
        }
    }

}

if (!function_exists('DetailsProfileShortCode')) {

    function DetailsProfileShortCode() {

        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        if((!empty($firm->account_type) && $firm->account_type == 'CMS') || Auth::User()->role_id == 2)  {
            $vv = DB::table('tila_pdfform_field_group')->where('shortcode', $_REQUEST['s'])->first();
            $FIelds = (getallchild($vv->ID, array(), 1));
            ?><h4><?php echo $vv->GroupName; ?> Information</h4>               

            <?php foreach ($FIelds as $v) { ?>
                <?php
                $ClientID = $_REQUEST['userid'];
                $q = "SELECT user_id FROM new_client WHERE id = '" . $ClientID . "'";
                $vsd = DB::select($q);

                GetProfilePdfField($v, $vsd[0]->user_id);
                ?>
                <?php
            }
        }
        else { ?>
            <div class="row">
                  <div class="col-md-12">
                      <br><br>
                      <div class="card card-info text-center">
                        <br>
                        <div class="card-body">
                          <h6>
                              <i class="fa fa-exclamation-triangle" style="font-size: 16px;"></i> 
                              This feature is for case management software users
                          </h6>
                          <h5 style="max-width: 320px;margin: 15px auto;">
                              Get full CMS access for your Firm we are all using it.
                          </h5>
                          <h5>
                              $<span class="annual_payment_cycletext"><?php echo $firm->usercost; ?> a month</span> <br> per user
                          </h5>

                          <label class="custom-switch mt-2">
                              <span class="custom-switch-description" style="margin: 0 .5rem 0 0;">Bill Annually</span> 
                              <input type="checkbox" name="payment_cycle" class="custom-switch-input annual_payment_cycle" value="1" checked data-monthly_amount="<?php echo $firm->usercost; ?>">
                              <span class="custom-switch-indicator"></span>
                              <span class="custom-switch-description">Bill Monthly</span>
                          </label>
                          <div class="saved_amount_text"></div>
                        </div>
                        <div class="card-footer">
                          <input type="hidden" name="amount" value="55">
                          <a href="<?php echo url('firm/upgradetocms'); ?>" class="btn btn-primary">Upgrade</a>
                        </div>
                      </div>
                   </div>
              </div>
        <?php }
    }

}


if (!function_exists('GetProfilePdfField')) {

    function GetProfilePdfField($data, $ClientID) {
#pre($data);
//echo $ClientID;  
        $q = "select (select Value from tila_pdfform_metaField_SaveData where FieldUniqueID=f.FieldUniqueID and UserID='" . $ClientID . "') as datavalue,f.FieldName,f.CustomFieldNameFront,f.fieldtype,f.FieldUniqueID from tila_pdfform_field_group_setFields as g,tila_pdfform_meta as f 
where f.FieldUniqueID=g.FieldUniqueID and g.FieldGroupID='" . $data->ID . "'";

        $v = DB::select($q);
        if (count($v) > 0) {
            echo '<h2>' . ucfirst($data->GroupName) . '</h2>';
        }
        if ($data->IsThisGroupRadio == 1)
            echo '<div class="rgpar">';
        foreach ($v as $v1) {
            $dataField['type'] = $v1->fieldtype;
            $dataField['UniqueKey'] = $v1->FieldUniqueID;
            $dataField['arrflag'] = $data->IsThisGroupRepeat;
            $dataField['value'] = $v1->datavalue;
            $dataField['rdoflag'] = $data->IsThisGroupRadio;

            echo '<div data-FieldId="' . $v1->FieldUniqueID . '" data-userId="' . $ClientID . '" class="pdfsave info-text-general"><label><span>';
            GetPdfFieldName($v1->CustomFieldNameFront, $v1->FieldName);
            echo '</span>';
            echo ByTypeCreateField($dataField);
            echo '</label></div>';
        }
        if ($data->IsThisGroupRadio == 1)
            echo '</div>';
    }

}

if (!function_exists('GetPdfFieldName')) {

    function GetPdfFieldName($name = '', $pdfname = '') {
        if ($name != 'NONAME') {
            echo $name;
        } else {
            $d = explode('_', $pdfname);
            $d = explode('[', $d[count($d) - 1]);
            echo ($d[0]);
        }
    }

}
if (!function_exists('ProfileDataSave')) {

    function ProfileDataSave() {
        SavePDFDataHelper($_REQUEST['FieldID'], $_REQUEST['Values'], $_REQUEST['UID']);
    }

}


if (!function_exists('getallchild')) {

    function getallchild($id, $data = array(), $flag = 0) {
        $vv = DB::table('tila_pdfform_field_group')->where('ParentGroupID', $id)->get();

        foreach ($vv as $v) {

            if ($flag == 0) {
                $data[$id] = $v->ID;
            } else {
                $data[$id] = $v;
            }
            $data = getallchild($v->ID, $data, $flag);
        }
        return $data;
    }

}
if (!function_exists('GETAllPDFForm')) {

    function GETAllPDFForm() {
        $keyword = $_REQUEST['s'];
        $PATH = storage_path('app/forms/all');
        $dirPath = scandir($PATH);


        unset($dirPath[array_search(".", $dirPath)]);
        unset($dirPath[array_search("..", $dirPath)]);

        $dPath = array_map('base64_encode', $dirPath);

        $datas = base64_encode(implode(',', $dPath));

        echo '<div class="row getallfiles" data-files="' . $datas . '">';
        foreach ($dirPath as $v) {
            if ($keyword == 'NoSearch' || strpos(strtolower($v), strtolower($keyword)) !== false) {
                ?>
                <div class="col-lg-1 col-sm-1 col-md-1 col-xs-12 pdffilesurlpath" style="padding: 5px 5px;" data-filter="<?php echo trim(explode('.', $v)[0]); ?>">
                    <div class="datasflt img-thumbnail rounded">
                        <a  href="/admin/pdfform/setupfields/<?php echo base64_encode($v); ?>">
                            <center>
                                <img style="width:100%;" src="/Pdficon.png">
                                <div style="color:#000 !important;font-size: 10px;    text-align: center !important;    text-transform: uppercase;"><?php echo explode('.', $v)[0]; ?></div>
                                <div style="text-align: center !important;"><a target="_blank" href="/admin/pdfform/viewpdf/<?php echo base64_encode($v); ?>">View</a></div>
                            </center>
                        </a>
                    </div>
                </div> <?php
            }
        }
    }

}
if (!function_exists('GETAllPDFFormMasterField')) {

    function GETAllPDFFormMasterField() {
        $n = '';
        if (isset($_REQUEST['Name']) && strlen($_REQUEST['Name']) > 0 && $_REQUEST['Name'] != 'undefined') {
            $n = trim($_REQUEST['Name']);
        }
        $select = 0;
        if (isset($_REQUEST['Selecttype']) && $_REQUEST['Selecttype'] != 'undefined') {
            $select = $_REQUEST['Selecttype'];
        }
        $drop[1] = 'NO Master Field';
        $drop[2] = 'Master Field';
        ?>
        <div class="FieldSearchingMaster">
            <div class="FieldSearchForMaster">
                <input value="<?php echo $n; ?>" type="text" class="form-control SearchByName" placeholder="Search Field">
                <select class="fieldtype form-control">
                    <?php
                    foreach ($drop as $k => $d) {
                        $sel = '';
                        if ($select == $k) {
                            $sel = 'selected="selected"';
                        }
                        ?>
                        <option <?php echo $sel; ?> value="<?php echo $k; ?>"><?php echo $d; ?></option>
                    <?php } ?>
                </select>
                <input type="button" class="form-control SearchbyFieldName" value="Field Search">
            </div>
            <?php
            $p = md5(($_REQUEST['pdf']));

            $NotMaster = array();

            $query = "SELECT * FROM tila_pdfform_meta    where ";
            if (isset($_REQUEST['Name']) && strlen($_REQUEST['Name']) > 1 && $_REQUEST['Name'] != 'undefined' && $select == 1) {
                $query .= "pdffileEncripted LIKE '%{$p}%' and (FieldName like '%{$n}%' or CustomFieldName like '%{$n}%' or CustomFieldNameFront  like '%{$n}%') and isMaterField=0 group by ID";
                $NotMaster = DB::select($query);
            } else {
                $NotMaster = DB::table('tila_pdfform_meta')->where('pdffileEncripted', $p)->where('isMaterField', 0)->get();
            }
            if (isset($_REQUEST['Name']) && strlen($_REQUEST['Name']) > 1 && $_REQUEST['Name'] != 'undefined' && $select == 2) {
                $query .= " (FieldName like '%{$n}%' or CustomFieldName like '%{$n}%' or CustomFieldNameFront  like '%{$n}%') and isMaterField=1 group by ID";
                $Master = DB::select($query);
            } else {
                $Master = DB::table('tila_pdfform_meta')->where('isMaterField', 1)->get();
            }
            //$Master = DB::table('tila_pdfform_meta')->where('isMaterField', 1)->get();
            ?>
            <div class="MasterFieldSelection">
                <div class="fields lefticonbox lefttboxdatacss">
                    <h3>Select From Here <span class="masterfield"></span></h3>

                    <ul>
                        <li><table style="width: 100%;"><tr><th style="width:33.33%">Part Number</th><th  style="width:33.33%">Question</th><th  style="width:33.33%">Name Given In PDF	</th></tr></table><a></a></li>
                        <?php
                        foreach ($NotMaster as $v) {
                            $v->OldName = $v->name = $v->FieldName;
                            $v->OldName = explode('_', $v->OldName);
                            $newName = '';
                            if ($v->CustomFieldName != 'NONAME' && strlen($v->CustomFieldName) > 0 && $v->CustomFieldName != 'undefined') {
                                $v->name = $v->CustomFieldName;
                                $newName = $v->CustomFieldName;
                            }
                            $pINdex=$v->pdfindex;
                            ?>
                            <li data-ismaster="<?php echo $v->isMaterField; ?>" data-ID="<?php echo $v->FieldUniqueID; ?>">
                                <span>

                                    <table><tr>
                                            <th  style="width:33.33%"><?php echo (isset($v->OldName[0]) ? $v->OldName[0] : ''); ?></th>
                                            <th  style="width:33.33%"><?php echo (isset($v->OldName[1]) ? $v->OldName[1] : ''); ?></th>
                                            <th  style="width:33.33%"><?php echo (isset($v->OldName[2]) ? $v->OldName[2] : ''); ?>  <?php echo 'IndexNo:'.$pINdex ?></th>
                                        </tr></table>
                                    <div>
                                        <input value="<?php echo $newName; ?>" class="autocompleteUpdateMasterField" type="text">
                                        <div class="suggesstion-box"></div>
                                        <i class="fa fa-spin fa-spinner hide"></i>
                                    </div>
                                </span>
                                <a class="Pushinbukate btn btn-danger">
                                    <i class=" fa fa-toggle-right"></i>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="fields lefticonbox RightBoxFOrMaster">
                    <h3>Master Field </h3> 
                    <ul>
                        <?php
                        foreach ($Master as $v) {
                            $v->name = $v->FieldName;
                            if ($v->CustomFieldName != 'NONAME' && strlen($v->CustomFieldName) > 0) {
                                $v->name = $v->CustomFieldName;
                            }
                            $v->name = '<section style="width: 88%;     font-size: 11px;"><a style="background: #777;    color: #fff;    float: left;    width: 55px;    text-align: center;    border-radius: 3px;    line-height: 1.6em;    margin-top: 5px;    margin-right: 4px;">' . $v->pdffile . '</a> : ' . $v->name . '</section>'; //pre($v);
                            ?>
                            <li data-ismaster="<?php echo $v->isMaterField; ?>"  data-ID="<?php echo $v->FieldUniqueID; ?>"><span><?php echo $v->name; ?></span><a class="Pushinbukate btn btn-danger"><i  class=" fa fa-times"></i></a></li>
                                <?php } ?>
                    </ul>
                </div>
            </div> 
        </div> 
        <?php
    }

}


if (!function_exists('GETAllPDFFormMasterFieldSTatusChanged')) {

    function GETAllPDFFormMasterFieldSTatusChanged() {

        $master = 0;
        if ($_REQUEST['m'] == 0) {
            $master = 1;
        }
        $name = 'NONAME';
        if ($_REQUEST['name'] != 'undefined' && strlen($_REQUEST['name']) > 3) {
            $name = base64_decode($_REQUEST['name']);
        }

        $l['isMaterField'] = $master;
        if ($_REQUEST['name'] != 'undefined') {
            $l['CustomFieldName'] = $name;
            $l['CustomFieldNameFront'] = $name;
        }
        DB::table('tila_pdfform_meta')->where('FieldUniqueID', $_REQUEST['f'])->update($l); 
    }

}

if (!function_exists('GETAllPDFFormMasterFieldNameChanged')) {

    function GETAllPDFFormMasterFieldNameChanged() {
        $master = trim($_REQUEST['m']);
        if (strlen($master) == 0) {
            $master = 'NONAME';
        }
        if (strlen($master) > 0) {
            $l['CustomFieldName'] = $master;
            $l['CustomFieldNameFront'] = $master;
            DB::table('tila_pdfform_meta')->where('FieldUniqueID', $_REQUEST['f'])->update($l);
        }
    }

}
if (!function_exists('GetAllFieldByFileNameJson')) {

    function GetAllFieldByFileNameJson($i, $j = 0) {
        $file = base64_decode($i);
        $row = 10;
        $h = $j;
        $j = $row * ($h + 1);
        $limit = 'limit ' . $j . ',' . $row;
        $FieldsData = 'SELECT *  from tila_pdfform_meta as m where pdffileEncripted="' . md5($file) . '" ' . $limit;
        $data['Next'] = count(DB::select($FieldsData));

        $j = $row * ($h - 1);
        if ($j < 0) {
            $data['pre'] = -1;
        } else {
            $limit = 'limit ' . $j . ',' . $row;
            $FieldsData = 'SELECT *  from tila_pdfform_meta as m where pdffileEncripted="' . md5($file) . '" ' . $limit;
            $data['pre'] = count(DB::select($FieldsData));
        }
        echo json_encode($data);
    }

}

if (!function_exists('GetAllFieldByFileNameTable')) {

    function GetAllFieldByFileNameTable($i, $j = 0) {
        $file = base64_decode($i);
        $row = 10;
        $j = $row * $j;
        $limit = 'limit ' . $j . ',' . $row;
        $FieldsData = DB::select('SELECT (select r.ParentFieldUniqueID from tila_pdfform_field_relationship as r where  r.ChildFieldUniqueID=m.FieldUniqueID) as Parent,m.* from tila_pdfform_meta as m where pdffileEncripted="' . md5($file) . '"' . $limit);
//pre($FieldsData);
        $Options = DB::select('SELECT  * from tila_pdfform_meta where isMaterField="1" ORDER BY ID DESC');
        $Groups = DB::select('SELECT  * from tila_pdfform_field_group');
        $htm = '';
        foreach ($FieldsData as $v) {

            $FieldType = '';

//echo base64_decode($RequestedData),$itr->Current()->GetName().'<br>';
            $uID = GetFieldKey($file, $v->FieldID);
            $d['type'] = GetFieldType($v->fieldtype);
            $d['FieldName'] = $v->FieldName;
            $d['FieldUniqueID'] = $uID;
            $d['pdffile'] = $v->pdffile;
            $d['FieldID'] = $v->FieldID;
            $dd = base64_encode(json_encode($d));
            $fieldNameg = explode('_', $v->FieldName);
//pre($fieldNameg);
            $fname = explode('[', $fieldNameg[count($fieldNameg) - 1]);
            $htm .= '<tr class="filefieldselection opcfull" data-upfield="' . $dd . '">';


            $fieldName = $fname[0];
            if ($v->CustomFieldName != 'NONAME') {
                $fieldName = $v->CustomFieldName;
            }

            $fieldNameFront = $fname[0];
            if ($v->CustomFieldNameFront != 'NONAME') {
                $fieldNameFront = $v->CustomFieldNameFront;
            }
            $htm .= '<td><i class="btn btn-success fa fa-save SaveName"></i></td>';
            $htm .= '<td style=" width: 100px !important;"> ' . chkNameShow($v->FieldName, 0) . ' </td>';
            $htm .= '<td style=" width: 100px !important;"> ' . chkNameShow($v->FieldName, 1) . ' </td>';
            $htm .= '<td> ' . chkNameShow($v->FieldName, 2) . ' </td>';
            $htm .= '<td> ' . GetFieldType($v->fieldtype) . '</td>';
//$htm .= '<td>' .$v->FieldName. '</td>';
            $htm .= '<td><input type="text" data-UID="' . $uID . '" class="FrontfieldNames" value="' . $fieldNameFront . '"></td>';
            $htm .= '<td  ><input type="text" data-UID="' . $uID . '" class="fieldNames" value="' . $fieldName . '"></td>';

//$htm .= '<td>' . $fieldNameg[0] . '</td>';
            $cc = '';
            if ($v->isMaterField == 0) {
                $cc = 'showIn';
            }
            $htm .= '<td  ><select data-live-search="true" class="FieldRelations selectpicker ' . $cc . '">';
            $htm .= '<option value="0">Select</option>';
            foreach ($Options as $o) {

                $fieldNameg1 = explode('_', $o->FieldName);
                $fname1 = explode('[', $fieldNameg1[count($fieldNameg1) - 1]);
                $fieldName1 = $fname1[0];
                if ($o->CustomFieldName != 'NONAME') {
                    $fieldName1 = $o->CustomFieldName;
                }
                $sel = '';
                if ($v->Parent == $o->FieldUniqueID) {
                    $sel = "selected='selected'";
                }
                $htm .= '<option ' . $sel . ' value="' . $o->FieldUniqueID . '">' . $fieldName1 . '</option>';
            }
            $htm .= '</select></td>';


            $OptionsN = DB::select('SELECT GROUP_CONCAT(FieldGroupID) as groupss FROM tila_pdfform_field_group_setFields WHERE FieldUniqueID = "' . $uID . '"');
            $g = '0';
            if (count($OptionsN) > 0) {
                $g = $OptionsN[0]->groupss;
            }
            $op = '';
            foreach ($Groups as $gs) {
                $gg = explode(',', $g);
                $sell = '';
                if (count($gg) > 0 && in_array($gs->ID, $gg)) {
                    $sell = 'selected="selected"';
                }
                $op .= '<option ' . $sell . ' value="' . $gs->ID . '" >' . GetGroupNameWithParentByID($gs->ID) . '</option>';
            }





            $htm .= '<td><select data-groupdata="' . $g . '" class="selectionBox selectpicker " data-actions-box="true" data-live-search="true" multiple="multiple">' . $op . '</select></td>';

//$htm .= '<td><select class="FieldRelations showIn"><option>ddfffff</option></select></td>';

            if ($v->isMaterField == 1) {
                $htm .= '<td><center><i data-UID="' . $uID . '" class="fieldIsMaster ">yes</i></center></td>';
            } else {
                $htm .= '<td><center><i data-UID="' . $uID . '"  class="fieldIsMaster ">No</i></center></td>';
            }
            $htm .= '<td><i class="btn btn-success fa fa-save SaveName"></i></td>';

            $htm .= '</tr>';
        }
        echo $htm;
    }

}



if (!function_exists('GetAllFieldByFileName')) {

    function GetAllFieldByFileName() {

        $file = base64_decode($_REQUEST['id']);
        if (isset($_REQUEST['v']) && $_REQUEST['v'] == 1) {
            $limit = 0;
            if (isset($_REQUEST['page'])) {
                $limit = $_REQUEST['page'];
            }
            GetAllFieldByFileNameJson($_REQUEST['id'], $limit);
        } else {
            $limit = 0;
            if (isset($_REQUEST['page'])) {
                $limit = $_REQUEST['page'];
            }
            GetAllFieldByFileNameTable($_REQUEST['id'], $limit);
        }
    }

}


if (!function_exists('FilterPdfDataTextMeta')) {

    function FilterPdfDataTextMeta() {
        $ID = (md5(base64_decode($_REQUEST['file'])));
        $q = "SELECT textUnique,metatext FROM tila_pdfform_meta_text WHERE fileEncription = '" . $ID . "' ";
        $Result = DB::select($q);
        $newd1 = $newd = array();

        foreach ($Result as $k => $v) {
            $newd[$v->textUnique] = ($v->metatext);
        }
        foreach ($newd as $k => $v) {
            $newd1[] = ($v);
        }
        //pre($Result);
        echo json_encode($newd1);
    }

}

if (!function_exists('getMasterFieldName')) {

    function getMasterFieldName() {
        $v = json_decode(file_get_contents('csv/csv.txt'));

        $FieldsData = 'SELECT CustomFieldName  from tila_pdfform_meta where isMaterField="1" and CustomFieldName!="NONAME" ';
        $data = (DB::select($FieldsData));
        $newd = array();
        foreach ($data as $v2) {
            $newd[] = md5(strtolower($v2->CustomFieldName));
        }

        $keyw = $_REQUEST['keyword'];
        if (isset($_REQUEST['keyword']) && strlen($_REQUEST['keyword']) > 0) {
            echo '<ul>';
            foreach ($v as $v1) {

                if (strlen($_REQUEST['keyword']) > 0 && !in_array(md5(strtolower($v1)), $newd) && strpos(strtolower($v1), strtolower($keyw)) !== false) {
                    echo '<li>' . $v1 . '</li>';
                }
            }
            echo '</ul>';
        }
    }

}
if (!function_exists('RemaingMasterFieldName')) {

    function RemaingMasterFieldName() {
        $l = 0;
        $v = json_decode(file_get_contents('csv/csv.txt'));

        $FieldsData = 'SELECT CustomFieldName  from tila_pdfform_meta where isMaterField="1" and CustomFieldName!="NONAME" ';
        $data = (DB::select($FieldsData));
        $newd = array();
        foreach ($data as $v2) {
            $newd[] = md5(strtolower($v2->CustomFieldName));
        }



        foreach ($v as $v1) {

            if (!in_array(md5(strtolower($v1)), $newd)) {
                $l++;
            }
        }
        echo $l;
    }

}