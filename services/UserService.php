<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."../dao/UserDAO.php");
use WRO\DAO as DAO;

class UserService {
    // Initializes the member dao to a new UserDAO
    public function __construct() {
        $this->dao_ = new DAO\UserDAO();
    }

    // Returns:
    //   Object[]
    //     ID            - ID of the user.
    //     UserName      - Name the user uses to log in.
    //     user_nicename - Sanitized UserName
    //     display_name  - Name user displays to others.
    public function GetAll() {
        return $this->dao_->GetAll();
    }

    private $dao_;
};