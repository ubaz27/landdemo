<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>jQuery Dynamic Form</title>

    <script crossorigin="anonymous" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>

<body>
    <h1>My Form</h1>
    <form id="my-form" action="/your-action-here">
        <div class="fields"></div>

        <p>
            <button type="button" class="add-fields">
                Add fields
            </button>

            <button type="submit">
                Send form
            </button>
        </p>
    </form>

    <!--
    text/x-templates is a fake type  just to tell
   the browser to ignore this script block
-->
    <table>
        <tr onclick="myFunction(this)">
            <td>Click to show rowIndex</td>
        </tr>
        <tr onclick="myFunction(this)">
            <td>Click to show rowIndex</td>
        </tr>
        <tr onclick="myFunction(this)">
            <td>Click to show rowIndex</td>
        </tr>
    </table>

    <script>
        function myFunction(x) {
            alert("Row index is: " + x.rowIndex);
        }
    </script>
    <script type="text/x-templates" id="fields-templates">
    <p class="input-fields">
        <input name="title[]" placeholder="title">
        <input name="description[]" placeholder="description">
        <button type="button" class="remove-fields">
            Remove these fields
        </button>
    </p>
</script>

    <script>
        alert("sdfsf");
        $(function() {
            var FIELDS_TEMPLATE = $('#fields-templates').text();
            var $form = $('#my-form');
            var $fields = $form.find('.fields');

            $form.on('click', '.add-fields', function() {
                $fields.prepend($(FIELDS_TEMPLATE));
            });

            $form.on('click', '.remove-fields', function(event) {
                $(event.target).closest('.input-fields').remove();
            });
        });
    </script>
</body>

</html>
