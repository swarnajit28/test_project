<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\User;
use App\model\User_type;
use App\model\Stored_document;
use helper;
use Validator;


class DocumentsManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->items_per_page = Config::get('formArray.items_per_page');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_documents(Request $request) {
        $formfield = helper::getFormFields("documentStore");
        $form_data = helper::encryptForm($formfield);
        if ($request->has('_token')) {
            $white_lists = $formfield;
            $ignore_keys = array('_token');
            $allRequest = $request->all();
            $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
            $file_data = helper::decryptForm($_FILES, $white_lists, $ignore_keys);
            $rule = [
                'doc_name' => 'required|min:3',
                'doc_type' => 'required|numeric',
                'doc_for' => 'required|numeric',
                'file_name' => 'required',
            ];
            $validator = Validator::make($post_data, $rule);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $file_ext = pathinfo($file_data['file_name']['name'], PATHINFO_EXTENSION);
            $microsec = explode(".", microtime(true));
            $file_name = "store_" . date('Ymd_His') . $microsec[1] . "." . $file_ext;
            $destination = "public/uploads/doc_store/" . $file_name;
            move_uploaded_file($post_data['file_name'], $destination);
            $arr['uploaded_file_name'] = $file_name;
            $arr['document_name'] = $post_data['doc_name'];
            $arr['doc_type'] = $post_data['doc_type'];
            $arr['user_type_id'] = $post_data['doc_for'];
            $arr['is_active'] = 1;
            $arr['created_by'] = Auth::user()->id;
            $result = Stored_document::firstOrCreate($arr);
             return redirect('/DocumentStore')->with('success_message', 'New Documents Distributed Successfully!');
        }
        $all_user_type = User_type::all_user_type();
        //helper::pre($all_user_type,1);
        return view('upload_documents', compact('all_user_type', 'form_data'));
    }

    public function view_document_store(Request $request){
        //helper::pre(Auth::user()->user_type);
        $search='no';
        $user_type=Auth::user()->user_type;
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        //helper::pre($request->all(),0);
        if ($request->has('_token')) {
            if ($request->input('fromdate') != '') {
                $postArr['fromdate'] = $request->input('fromdate');
            }
            if ($request->input('todate') != '') {
                $postArr['todate'] = $request->input('todate');
            }
            $search='yes';
            $all_data = Stored_document::searchQuerie($postArr,$user_type);
            //$all_data = Stored_document::fetch_all_store_document($per_page);
            //helper::pre($all_data,0);
        } else {
            $per_page = $this->items_per_page;
            $all_data = Stored_document::fetch_all_store_document($per_page,$user_type);
        }
        foreach ($all_data as $key => $value) {
            $ext = explode('.', $value['uploaded_file_name']);
            $all_data[$key]['ext'] = $ext[1];
            if($value['doc_type']=='1'){
               $all_data[$key]['doc_type']='Sales Literature';
            }elseif($value['doc_type']=='2'){
               $all_data[$key]['doc_type']='Monthly/Quarterly Reports';
            }elseif($value['doc_type']=='3'){
               $all_data[$key]['doc_type']='Archive Report';
            }elseif($value['doc_type']=='4'){
               $all_data[$key]['doc_type']='Historic Sales';
            }
        }
        //helper:: pre($all_data,1);
        return view('document_store', compact('all_data','postArr','search'));
        //return view('document_store');
    }

     public function load_document_store(Request $request) {
        $user_type=Auth::user()->user_type;
        $output = '';
        $id = $request->input('id');
        $page = $this->items_per_page;
        $all_data = Stored_document::get_load_document_store($page, $id,$user_type);
        foreach ($all_data as $key => $value) {
            $ext = explode('.', $value['uploaded_file_name']);
            $all_data[$key]['ext'] = $ext[1];
            if ($value['doc_type'] == '1') {
                $all_data[$key]['doc_type'] = 'Sales Literature';
            } elseif ($value['doc_type'] == '2') {
                $all_data[$key]['doc_type'] = 'Monthly/Quarterly Reports';
            } elseif ($value['doc_type'] == '3') {
                $all_data[$key]['doc_type'] = 'Archive Report';
            } elseif ($value['doc_type'] == '4') {
                $all_data[$key]['doc_type'] = 'Historic Sales';
            }
        }
        //dd($all_data);
        if (count($all_data) > 0) {
            foreach ($all_data as $key=>$data) {
                echo($key);
                $output .= '<tr id="' . $data['id'] . '"><td>DOC_' . $data['id'] . '</td>'
                        . '<td>' . $data['document_name'] . '</td>'
                        . '<td>' . $data['doc_type'] . '</td>';
                if($user_type=='IT'){
                 $output .= '<td>' . $data['user_type'] . '</td>';
                }
                if ($data['ext'] == 'png' || $data['ext'] == 'jpeg' || $data['ext'] == 'jpg') {
                    $output .= '<td data-hide="phone,tablet"><a href="' . asset('public/uploads/doc_store/') . '/' . $data['uploaded_file_name'] . '" target="_blank" title="Image"><i class="fa fa-file-image-o" aria-hidden="true"></i></a></td>';
                } elseif ($data['ext'] == 'pdf') {
                    $output .= '<td data-hide="phone,tablet"><a href="' . asset('public/uploads/doc_store/') . '/' . $data['uploaded_file_name'] . '" target="_blank" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>';
                } elseif ($data['ext'] == 'csv' || $data['ext'] == 'xlsx') {
                    $output .= '<td data-hide="phone,tablet"><a href="' . asset('public/uploads/doc_store/') . '/' . $data['uploaded_file_name'] . '" target="_blank" title="Reports"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a></td>';
                } elseif ($data['ext'] == 'zip' || $data['ext'] == 'rar') {
                    $output .= '<td data-hide="phone,tablet"><a href="' . asset('public/uploads/doc_store/') . '/' . $data['uploaded_file_name'] . '" target="_blank" title="Archive"><i class="fa fa-file-archive-o" aria-hidden="true"></i></a></td>';
                } else {
                    $output .= '<td data-hide="phone,tablet"><a href="' . asset('public/uploads/doc_store/') . '/' . $data['uploaded_file_name'] . '" target="_blank" title="Document"><i class="fa fa-cubes" aria-hidden="true"></i></a></td>';
                }

                $output .= '<td>' . date('d/m/Y', strtotime($data['created_on'])) . '</td>';
            }
        }
        //helper::pre($output,1);
        echo $output;
    }

}
