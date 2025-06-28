<?php
class Blog extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Blog_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper(['form', 'url']);
    }

    public function index() {
        $data['posts'] = $this->Blog_model->get_all_posts();
        $this->load->view('blog/index', $data);
    }

    public function create()
    {
        $this->load->library(['form_validation', 'session']);

        try {
            // Validation Rules
            $this->form_validation->set_rules('title', 'Title', 'required|min_length[5]');
            $this->form_validation->set_rules('content', 'Content', 'required|min_length[10]');

            // Run Validation
            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                throw new Exception($errors);
            }

            // Handle image upload if present
            $image = null;
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path']   = './uploads/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size']      = 2048;
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('image')) {
                    throw new Exception($this->upload->display_errors('', ''));
                }

                $uploadData = $this->upload->data();
                $image = $uploadData['file_name'];
            }

            // Prepare data
            $data = [
                'title'      => $this->input->post('title', TRUE),
                'body'       => $this->input->post('content', FALSE),
                'image'      => $image,
                'categories' => $this->input->post('categories', TRUE),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Attempt insert
            $this->Blog_model->insert_post($data);

            // Success response
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success']);
                return;
            }

            $this->session->set_flashdata('success', 'Blog post created successfully');
            redirect('blog');

        } catch (Exception $e) {
            // Check for duplicate title error (if title column is UNIQUE)
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'Duplicate entry') !== false) {
                $errorMsg = 'A blog post with this title already exists.';
            }

            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => $errorMsg]);
                return;
            }

            $this->session->set_flashdata('error', $errorMsg);
            $this->load->view('blog/add');
        }
    }

    public function edit($id)
    {
        $this->load->library(['form_validation', 'session']);

        // Get post
        $post = $this->Blog_model->get_post($id);
        if (!$post) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Post not found.']);
            } else {
                show_404();
            }
            return;
        }

        // Set validation rules
        $this->form_validation->set_rules('title', 'Title', 'required|min_length[5]');
        $this->form_validation->set_rules('content', 'Content', 'required|min_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $errorMsg = validation_errors();

            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => $errorMsg]);
                return;
            } else {
                $data['post'] = $post;
                $this->load->view('blog/edit', $data);
                return;
            }
        }

        // Handle image upload
        $image = $post['image'];
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $uploadData = $this->upload->data();
                $image = $uploadData['file_name'];

                if (!empty($post['image']) && file_exists('./uploads/' . $post['image'])) {
                    unlink('./uploads/' . $post['image']);
                }
            } else {
                $uploadError = $this->upload->display_errors('', '');
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => $uploadError]);
                    return;
                } else {
                    $data['post'] = $post;
                    $data['upload_error'] = $uploadError;
                    $this->load->view('blog/edit', $data);
                    return;
                }
            }
        }

        // Prepare update data
        $updateData = [
            'title'      => $this->input->post('title', TRUE),
            'body'       => $this->input->post('content', FALSE),
            'categories' => $this->input->post('categories', TRUE),
            'image'      => $image
        ];

        $this->Blog_model->update_post($id, $updateData);

        if ($this->input->is_ajax_request()) {
            echo json_encode(['status' => 'success', 'message' => 'Post updated successfully.']);
        } else {
            $this->session->set_flashdata('success', 'Post updated successfully.');
            redirect('blog');
        }
    }



    public function delete($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404(); // Only allow AJAX calls
        }

        // Get post to access the image filename
        $post = $this->Blog_model->get_post($id);
        if (!$post) {
            echo json_encode(['status' => 'error', 'message' => 'Post not found.']);
            return;
        }

        // Delete image if it exists
        if (!empty($post['image']) && file_exists('./uploads/' . $post['image'])) {
            unlink('./uploads/' . $post['image']);
        }

        // Delete the post record
        $deleted = $this->Blog_model->delete_post($id);

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Post deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Unable to delete post from database.']);
        }
    }



    public function filter_posts()
    {
        $startDate = $this->input->get('startDate'); // or use 'post' if you're using POST
        $endDate = $this->input->get('endDate');
        $category = $this->input->get('category');

        $filteredPosts = $this->Blog_model->filter_posts($startDate, $endDate, $category);

        echo json_encode(['status' => 'success', 'posts' => $filteredPosts]);
    }

}
