<?php



class LeadData{
    private $dbHost = 'localhost';
    private $dbUser = 'root';
    private $dbPassword = '';
    private $dbName = 'kalam_academy';

    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }



    public function fetchPaginatedLeadData($page, $perPage) {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM crm_lead_master_data LIMIT $perPage OFFSET $offset";
        $result = $this->conn->query($query);
    
        return ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function getTotalPages($perPage) {
        $query = "SELECT COUNT(*) as total FROM crm_lead_master_data";
        $result = $this->conn->query($query);
        $totalRows = ($result) ? $result->fetch_assoc()['total'] : 0;
    
        return ceil($totalRows / $perPage);
    }



    public function fetchLastDORAndCallerForAllUsers() {
        $allUsersData = array();
    
        $userQuery = "SELECT DISTINCT Admin_ID FROM crm_admin";
        $userResult = $this->conn->query($userQuery);
    
        if ($userResult) {
            while ($userRow = $userResult->fetch_assoc()) {
                $userId = $userRow['Admin_ID'];
    
                $lastDORQuery = "SELECT MAX(DOR) as Summary_Note FROM crm_calling_status WHERE Lead_ID = $userId";
                $result = $this->conn->query($lastDORQuery);
    
                
    
                $summaryNote = ($result->num_rows > 0) ? $result->fetch_assoc()['Summary_Note'] : null;
    
                $callerQuery = "SELECT name as Caller FROM crm_admin WHERE Admin_ID = $userId";
                $result = $this->conn->query($callerQuery);
    
                
    
                $caller = ($result->num_rows > 0) ? $result->fetch_assoc()['Caller'] : null;
    
                $allUsersData[] = ['Admin_ID' => $userId, 'Summary_Note' => $summaryNote, 'Caller' => $caller];
            }
        } else {
            die("User query failed: " . $this->conn->error);
        }
    
        return $allUsersData;
    }
    
    

    
}



?>