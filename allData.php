<?php

include "classes/LeadData.php";

$leadDataManager = new LeadData();

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 10;


$leadData = $leadDataManager->fetchPaginatedLeadData($page, $perPage);

$allUsersData = $leadDataManager->fetchLastDORAndCallerForAllUsers();

echo "<script>
$(document).ready( function () {
    $('#leadDataTable').DataTable();
});
</script>";


echo "<h2>All Data</h2>";
echo "<div class='table-responsive'>"; 
echo "<table id='leadDataTable' class='table table-bordered'>";
echo "<thead class='thead-dark'>"; 
echo "<tr><th>Lead_ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Alternate Mobile</th><th>Whatsapp</th><th>Interested In</th><th>Status</th><th>Source</th><th>Summary DOR</th><th>Caller</th></tr>";
echo "</thead>";

echo "<tbody>";

foreach ($leadData as $lead) {
    echo "<tr>";
    echo "<td>{$lead['Lead_ID']}</td>";
    echo "<td>{$lead['Name']}</td>";
    echo "<td>{$lead['Email']}</td>";
    echo "<td>{$lead['Mobile']}</td>";
    echo "<td>{$lead['Alternate_Mobile']}</td>";
    echo "<td>{$lead['Whatsapp']}</td>";
    echo "<td>{$lead['Interested_In']}</td>";
    echo "<td>{$lead['Status']}</td>";
    echo "<td>{$lead['Source']}</td>";

    $matchingUserData = null;
    foreach ($allUsersData as $userData) {
        if ($userData['Admin_ID'] == $lead['Lead_ID']) {
            $matchingUserData = $userData;
            break;
        }
    }

    if ($matchingUserData) {
        echo "<td>{$matchingUserData['Summary_Note']}</td>";
        echo "<td>{$matchingUserData['Caller']}</td>";
    } else {
        echo "<td>N/A</td>";
        echo "<td>N/A</td>";
    }

    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>"; 



// Pagination links

$totalPages = $leadDataManager->getTotalPages($perPage);

echo "<nav aria-label='Page navigation'>";
echo "<ul class='pagination pagination-sm'>";

for ($i = 1; $i <= $totalPages; $i++) {
    echo "<li class='page-item" . ($i == $page ? " active" : "") . "'>";
    echo "<a class='page-link' href='?page=$i'>$i</a>";
    echo "</li>";
}

echo "</ul>";
echo "</nav>";

