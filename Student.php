<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('stud_model');
        $this->load->helper('custom_helper');
        $this->load->library('form_validation');
    
    }	
	public function index(){
        //$this->load->database();
       
       // $data['query'] = $this->Stud_model->get();
        $this->db->select('*');
        $this->db->from('students');
        $this->db->join('mark_list', 'students.roll_no = mark_list.roll_no');
        $result = $this->db->get()->result();
        //$result=$this->Stud_model->get()->result();
        $this->load->view('Student_view',array('data'=>$result));  
       // $this->load->view('Student_view', $data);
        //rteyr
	    
            
	}
	public function create(){

        $config_validation = array(
            array('field'=>'fname','label'=>'First Name','rules'=>'trim|required'),
            array('field'=>'lname','label'=>'Last Name','rules'=>'trim|required'),
            array('field'=>'age','label'=>'Age','rules'=>'trim|numeric|required'),
            array('field'=>'address','label'=>'Address','rules'=>'trim|required'),
            array('field'=>'class','label'=>'class','rules'=>'trim|required'),
            array('field'=>'subject','label'=>'subject','rules'=>'trim|required'),
            array('field'=>'mark','label'=>'mark','rules'=>'trim|required')
        );

        $this->form_validation->set_rules($config_validation);
        if($this->form_validation->run()==FALSE){
           
            $this->load->view('Student_form');
        }
            
        else{
            $data = array(
                'fname'=> $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'age' => $this->input->post('age'),
                'address'=> $this->input->post('address'),
                'class'=> $this->input->post('class')
               
            );
            $this->stud_model->insert('students',$data);
            $insert_id = $this->db->insert_id();
        
            $data = array(
                'roll_no'=>$insert_id,
                'subject'=> $this->input->post('subject'),
                'marks'=> $this->input->post('mark')
            );
           
            $this->stud_model->insert('mark_list',$data);
            $this->session->set_flashdata('success','Data inserted successfully');
            redirect(base_url().'student/create');
            //$this->load->view('student_form');
        }  
    } 
    public function delete($id){
        $this->db->delete("students","roll_no=".$id);
        $this->db->delete("mark_list","roll_no=".$id);
        redirect(base_url().'Student');
    } 
    public function edit($id){
        $config_validation = array(
            array('field'=>'fname','label'=>'First Name','rules'=>'trim|required'),
            array('field'=>'lname','label'=>'Last Name','rules'=>'trim|required'),
            array('field'=>'age','label'=>'Age','rules'=>'trim|numeric|required'),
            array('field'=>'address','label'=>'Address','rules'=>'trim|required'),
            array('field'=>'class','label'=>'class','rules'=>'trim|required'),
            array('field'=>'subject','label'=>'subject','rules'=>'trim|required'),
            array('field'=>'mark','label'=>'mark','rules'=>'trim|required')
        );
        $this->form_validation->set_rules($config_validation);
        if($this->form_validation->run()==FALSE){
            $result['a'] = $this->stud_model->edit('students',array('roll_no'=>$id));
            $result['b'] = $this->stud_model->edit('mark_list',array('roll_no'=>$id));
            $this->load->view('Student_form',array('data'=>$result));
        }else{
            $data = array( 
            'fname'=> $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'age' => $this->input->post('age'),
            'address'=> $this->input->post('address'),
            'class'=> $this->input->post('class')
        );
            $this->stud_model->update('students',$data,array('roll_no'=>$id)); 
            //$this->db->where('roll_no'=>$id);
            //$this->db->update('student'=>$data);    
            $data = array(
                'subject'=> $this->input->post('subject'),
                'marks'=> $this->input->post('mark')
            );
            $this->stud_model->update('mark_list',$data,array('roll_no'=>$id));
           // $this->db->where('roll_no'=>$id);
            //$this->db->update('mark_list',$data);   
            $this->session->set_flashdata('success','Data inserted successfully');
            redirect(base_url().'Student/');
        }
    }
}	
   