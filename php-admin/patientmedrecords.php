<?php
session_start();

include('../database/config.php');
include('../php/user.php');
include('../php/medicine.php');
include('../php/patient.php');
include('../php/medicalrecords.php');

$db = new Database();
$conn = $db->getConnection();

$patient = new PatientManager($db);
$user = new User($conn);
$medicalrecords = new MedRecManager($conn);

$user_id = $_SESSION['user_id']; 
$userData = $user->getUserData($user_id);

if (isset($_SESSION['id']) && isset($_SESSION['type'])) {
    $patientId = $_SESSION['id'];
    $patientType = $_SESSION['type'];

} else {
    echo "No patient data found.";
}

?>
<!DOCTYPE html>
<html lang="en">    

<body>
<div class="row">
<h3 class="fw-bold mb-3">Manage Patient's Medical Records</h3>
</div>
<div class="col-md-12">
            <div class="card card-equal-height">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">List of Medical Records</h4>
                        <button
                        class="btn btn-primary btn-round ms-auto"
                        data-bs-toggle="modal"
                        data-bs-target="#addMedicalRecModal"
                      >
                        <i class="fa fa-plus"></i>
                        Add Medical Record 
                      </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Modal -->
                    <div
                      class="modal fade"
                      id="addMedicalRecModal"
                      tabindex="-1"
                      role="dialog"
                      aria-hidden="true"
                    >
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header border-0">
                            <h5 class="modal-title">
                              <span class="fw-mediumbold"> New</span>
                              <span class="fw-light"> Medicine </span>
                            </h5>
                            <button
                              type="button"
                              class="close"
                              data-dismiss="modal"
                              aria-label="Close"
                            >
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="small">
                            </p>
                            <form class="form" action="patientmedrecscontrol.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" id="patientid" name="patientid" value="<?php echo $patientId; ?>" />
                            <input type="hidden" class="form-control" id="patienttype" name="patienttype" value="<?php echo $patientType; ?>" />

                                <div class="row">
                                    <!-- Upload PDF (Medical Record File) -->
                                    <div class="col-md-12">
                                    <div class="form-group form-group-default">
                                        <label>Upload Medical Record (PDF only)</label>
                                        <input
                                            id="uploadfile"
                                            name="uploadfile[]"
                                            type="file"
                                            class="form-control"
                                            accept="application/pdf"
                                            multiple  
                                        />
                                    </div>
                                    </div>
                                </div>

                                <!-- Submit and Close Buttons -->
                                <div class="modal-footer border-0">
                                    <button
                                    type="submit"
                                    name="addmedicalrecs"
                                    class="btn btn-primary"
                                    >
                                    Add Medical Record
                                    </button>
                                    <button
                                    type="button"
                                    class="btn btn-danger"
                                    data-bs-dismiss="modal"
                                    >
                                    Close
                                    </button>
                                </div>
                            </form>

                          </div>
                        </div>
                      </div>
                    </div>

                    <?php
                    // Assume $patientid is defined elsewhere or passed via a request
                    $records = $medicalrecords->getMedicalRecords($patientId);
                    ?>

                    <div class="table-responsive">
                        <table id="addmedrecord" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>File Name</th>
                                    <th>Comment</th>
                                    <th>Date & Time Added</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>File Name</th>
                                    <th>Comment</th>
                                    <th>Date & Time Added</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if (!empty($records)) : ?>
                                    <?php foreach ($records as $record) : ?>
                                        <tr>
                                            <td><?= $record->medicalrec_id ?></td>
                                            <td><?= htmlspecialchars($record->medicalrec_filename) ?></td>
                                            <td><?= htmlspecialchars($record->medicalrec_comment) ?></td>
                                            <td><?= htmlspecialchars($record->medicalrec_dateadded . ' ' . $record->medicalrec_timeadded) ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                <button
    type="button"
    data-bs-toggle="tooltip"
    class="btn btn-link btn-primary btn-lg viewButton"
    data-file="<?= htmlspecialchars($record->medicalrec_file) ?>" 
    data-name="<?= htmlspecialchars($record->medicalrec_filename) ?>" 
    onclick="window.open('viewmedrecpdf.php?file=' + encodeURIComponent(this.getAttribute('data-file')) + '&name=' + encodeURIComponent(this.getAttribute('data-name')), '_blank');"
>
    <i class="fa fa-eye"></i>
</button>

                                                    <button
                                                        type="button"
                                                        data-bs-toggle="tooltip"
                                                        class="btn btn-link btn-primary btn-lg editButton"
                                                        data-id="<?= $record->medicalrec_id ?>"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        data-bs-toggle="tooltip"
                                                        class="btn btn-link btn-primary btn-lg deleteButton"
                                                        data-id="<?= $record->medicalrec_id ?>"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5">No records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
</div>
<script>
$(document).ready(function () {
    
    $("#addmedrecord").DataTable({
        pageLength: 5, 
        responsive: true, 
    });
});
</script>

</body>
</html>


