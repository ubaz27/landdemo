<html>

<head>

</head>

<body>
    <table class="table table-bordered table-danger">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="myTable">
            <tr>
                <form action="" method="">
                    <td><input type="text" name="name" id="name" class="form-control"
                            placeholder="Enter Your Name" required></td>
                    <td><input type="tel" name="tel" id="tel" class="form-control"
                            placeholder="Enter Your Phone Number" required></td>
                    <td><input type="text" name="address" id="address" class="form-control"
                            placeholder="Enter Current Residence" required></td>
                    <td><button class="btn btn-success" type="submit">Save</button> <button class="btn btn-danger"
                            type="button" onclick="myDeleteFunction()">Delete</button></td>
                </form>
            </tr>
        </tbody>


    </table>
    </div>

    <div style="float: right !important;">
        <button class="btn btn-secondary" style="margin-right: 20px;">Save All</button>
        <button class="btn btn-primary" onclick="myCreateFunction()">Add</button>
    </div>

    </div>
    <script src="{{ asset('assets/js/jquery2.min.js') }}"></script>
    <script>
        function myCreateFunction() {
            var table = document.getElementById("myTable");
            var row = table.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            cell1.innerHTML +=
                '<form action="" method=""><input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name">';
            cell2.innerHTML +=
                '<input type="tel" name="tel" id="tel" class="form-control" placeholder="Enter Your Phone Number">';
            cell3.innerHTML +=
                '<input type="text" name="address" id="address" class="form-control" placeholder="Enter Current Residence">';
            cell4.innerHTML +=
                '<button class = "btn btn-success" type="submit">Save</button> </form> <button class="btn btn-danger" type="button" onclick="myDeleteFunction()">Delete</button>';
        }

        function myDeleteFunction() {
            document.getElementById("myTable").deleteRow(0);
        }
    </script>
</body>

</html>
