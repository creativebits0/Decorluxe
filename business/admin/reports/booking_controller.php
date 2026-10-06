<?php
require_once '../../../data/admin/booking_model.php';


function getBookingReport($filters){
    return getBookingReportData($filters);
}

function getAllClients(){
    return fetchAllClients();
}

function getAllServices(){
    return fetchAllServices();
}

?>