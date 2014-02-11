{php_open} if (!defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * Controller {table}
 * @created on : {tanggal}
 * @author Daud D. Simbolon <daud.simbolon@gmail.com>
 * Copyright {year}
 *
 *
 */


class {table} extends MY_Controller
{

    public function __construct() 
    {
        parent::__construct();         
        $this->load->model('{table}s');
    }
    

    /**
    * List all data {table}
    *
    */
    public function index() 
    {
        $config = array(
            'base_url'          => site_url('{table}/index/'),
            'total_rows'        => $this->{table}s->count_all(),
            'per_page'          => $this->config->item('per_page'),
            'uri_segment'       => 3,
            'num_links'         => 9,
            'use_page_numbers'  => FALSE
            
        );
        
        $this->pagination->initialize($config);
        $data['total']          = $config['total_rows'];
        $data['pagination']     = $this->pagination->create_links();
        $data['number']         = $this->uri->segment(3);
        $data['{table}s']       = $this->{table}s->get_all($config['per_page'], $this->uri->segment(3));
        $this->template->render('{table}/view',$data);
	      
    }

    

    /**
    * Call Form to Add  New {table}
    *
    */
    public function add() 
    {       
        $data['{table}'] = $this->{table}s->add();
        $data['action']  = '{table}/save';
        
        $this->template->js_add('
                $(document).ready(function(){
                // binds form submission and fields to the validation engine
                $("#form_{table}").parsley("validate");
                        });','embed');
      
        $this->template->render('{table}/form',$data);

    }

    

    /**
    * Call Form to Modify {table}
    *
    */
    public function edit($id='') 
    {
        if ($id != '') 
        {

            $data['{table}'] = $this->{table}s->get_one($id);
            $data['action']       = '{table}/save/' . $id;           
            
           
            $this->template->js_add('
                     $(document).ready(function(){
                    // binds form submission and fields to the validation engine
                    $("#form_{table}").parsley("validate");
                                    });','embed');
            
            $this->template->render('{table}/form',$data);
            
        }
        else 
        {
            $this->session->set_flashdata('notif', alert('Data tidak ditemukan','info'));
            redirect(site_url('{table}'));
        }
    }


    
    /**
    * Save & Update data  {table}
    *
    */
    public function save($id =NULL) 
    {
        // validation config
        $config = array(
                  {fields_save}
                    array(
                        'field' => '{field_name}',
                        'label' => '{label}',
                        'rules' => 'trim|required|xss_clean'
                        ),
                    {/fields_save}           
                  );
            
        // if id NULL then add new data
        if(!$id)
        {    
                  $this->form_validation->set_rules($config);

                  if ($this->form_validation->run() == TRUE) 
                  {
                      if ($this->input->post()) 
                      {
                          
                          $this->{table}s->save();
                          $this->session->set_flashdata('notif', alert('Data berhasil di simpan','success'));
                          redirect('{table}');
                      }
                  } 
                  else // If validation incorrect 
                  {
                      $this->add();
                  }
         }
         else // Update data if Form Edit send Post and ID available
         {               
                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == TRUE) 
                {
                    if ($this->input->post()) 
                    {
                        $this->{table}s->update($id);
                        $this->session->set_flashdata('notif', alert('Data berhasil di update','success'));
                        redirect('{table}');
                    }
                } 
                else // If validation incorrect 
                {
                    $this->edit($id);
                }
         }
    }

    
    /**
    * Search {table} like ""
    *
    */   
    public function search($keyword='',$offset=0)
    {
        if($this->input->post('q'))
        {
            $keyword = $this->input->post('q');
        }
        
         $config = array(
            'base_url'          => site_url('{table}/search/' . $keyword),
            'total_rows'        => $this->{table}s->count_all_search($keyword),
            'per_page'          => $this->config->item('per_page'),
            'uri_segment'       => 4,
            'num_links'         => 9,
            'use_page_numbers'  => FALSE
        );
        
        $this->pagination->initialize($config);
        $data['total']          = $config['total_rows'];
        $data['number']         = $this->uri->segment(4);
        $data['pagination']     = $this->pagination->create_links();
        $data['{table}s']       = $this->{table}s->get_search($config['per_page'], $this->uri->segment(4),$keyword);
       
        $this->template->render('{table}/view',$data);
    }
    
    
    /**
    * Delete {table} by ID
    *
    */
    public function delete($id) 
    {        
        if ($id) 
        {
            $this->{table}s->delete($id);           
             $this->session->set_flashdata('notif', alert('Data berhasil di hapus','success'));
             redirect('{table}');
        } 
        else 
        {
            $this->session->set_flashdata('notif', alert('Data tidak ditemukan','warning'));
            redirect('{table}');
        }       
    }

}

{php_close}
