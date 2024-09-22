<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>
    <h1>Add Medicine</h1>
    <form class="form" action="medicinecontrol.php" method="POST">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group form-group-default">
                                    <label>Category</label>
                                    <input
                                      id="addcategory"
                                      name="addcategory"
                                      type="text"
                                      class="form-control"
                                      placeholder="fill category"
                                    />
                                  </div>
                                </div>
                                <div class="col-md-6 pe-0">
                                  <div class="form-group form-group-default">
                                    <label>Name</label>
                                    <input
                                      id="addname"
                                      name="addname"
                                      type="text"
                                      class="form-control"
                                      placeholder="fill name"
                                    />
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group form-group-default">
                                    <label>Quantity</label>
                                    <input
                                      id="addquantity"
                                      name="addquantity"
                                      type="number"
                                      class="form-control"
                                      placeholder="fill quantity"
                                    />
                                  </div>
                                </div>
                                <div class="col-md-6 pe-0">
                                  <div class="form-group form-group-default">
                                    <label>Dosage Strength</label>
                                    <input
                                      id="addDS"
                                      name="addDS"
                                      type="text"
                                      class="form-control"
                                      placeholder="fill dosage strength"
                                    />
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group form-group-default">
                                    <label>Expiration Date</label>
                                    <input
                                      id="addED"
                                      name="addED"
                                      type="date"
                                      class="form-control"
                                      placeholder="fill expiration date"
                                    />
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer border-0">
                                <button
                                  type="submit"
                                  name="addMedicine"
                                  class="btn btn-primary"
                                >
                                  Add
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
</body>
</html>
