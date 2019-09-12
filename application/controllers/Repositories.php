<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Repositories extends CI_Controller {

	public function __construct()
	{	// Standard constructor
		parent::__construct();
		$this->load->model('repositories_model');
		$this->load->helper('url_helper');
	}

	public function index()
	{	// Starting point for the views, lists the different repositories
		$data['repolist'] = $this->repositories_model->get_records();
		$data['title'] = 'GitHub Repositories';

		$this->load->view('templates/header', $data);
		$this->load->view('Repositories_list', $data);
		$this->load->view('templates/footer');
	}

	public function view($id = NULL)
	{
		$data['repo_info'] = $this->repositories_model->get_records($id);

        if (empty($data['repo_info']))
        {
                show_404();
        }

        $data['title'] = $data['repo_info']['Name'];

        $this->load->view('templates/header', $data);
        $this->load->view('Repositories_details', $data);
        $this->load->view('templates/footer');
	}
	
	public function update()
	{
		$this->repositories_model->update_records();
		$this->index();
		//redirect( site_url('') );
	}
}
