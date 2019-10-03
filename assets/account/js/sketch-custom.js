var sketchLayout = function() {
//Hides the Header Left in window size less than 992px	
    var handleHeaderLeft = function() {

        if (Metronic.getViewPort().width < 992) {
            alert("Hi");
            jQuery('.page-header .page-header-inner').children('.page-header-left').removeClass('page-header-left').addClass('page-false-header');


            var newChildren = $('.page-false-header');
            newChildren.empty();
            return;

        }//if

    }// handleHeaderLeft



    // All init Functions

    return {
        init: function() {
            //hide now
            handleHeaderLeft();

        }//init



    }//return


}();//sketchLayout

var sketchDataTreetable = function() {

    return {
        init: function() {

            $('#sample_7').dataTable({
                "scrollY": "300",
                "ordering": false,
                "bSort": false,
                "initComplete": function(settings, json) {
                    $(this).treetable();
                },
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                "pageLength": 10 // set the initial value 



            });

            var tableWrapper = $('#sample_7_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown	
            //$("#sample_5").treetable();

            $('#sample_7 .indenter').each(function() {
                $(this).parent('#sample_7 tr td').addClass('bold-parent');

            });//each	
        }



    }//return





}();

var listViewTree = function() {


    return{
        init: function() {

            var options = {
                valueNames: ['name'],
                page: 20,
                plugins: [
                    ListPagination({})
                ]
            };

            var userList = new List('users', options);


        }//init



    }//return




}();