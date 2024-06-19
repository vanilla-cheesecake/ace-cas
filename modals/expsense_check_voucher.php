<style>
    /* Customize modal width */
    /* .modal-custom {
        max-width: 100%;
        width: 100%;

    } */

    label {
        font-size: 11px;
    }

    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width: 1200px;
        }
    }

    /* Fix header when scrolling */
    #itemTable thead th {
        position: sticky;
        top: 0;
        background-color: white;
        /* Ensure header is visible on scroll */
        z-index: 1;
        /* Ensure header is above other elements */
    }

    /* Prevent text wrapping */
    #itemTable thead th {
        white-space: nowrap;
    }
</style>

<!-- modal_template.html -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="./photos/logo.jpg" alt="AdminLTE Logo" class="brand-image image-fluid"
                    style="opacity: .8 width: 50px; height: 50px">
                <h5 class="modal-title" id="exampleModalLabel">Check Voucher - Expense</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>

        </div>

    </div>
</div>
</div>