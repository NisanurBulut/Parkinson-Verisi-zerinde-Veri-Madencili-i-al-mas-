$(document).ready(function () {

    $('#editAppForm').find('input').trigger('keyup');
        $("#inputSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#divSearchContent .card").filter(function() {
              console.log(value,$(this).text().toLowerCase());
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });


    $(document).on('click','#confirmCloseBtn',function(){
        $('#confirmModal').modal('hide');
    });

    window.countInput = function (item, labelName) {
        event.preventDefault();
        let inputContent = $(item).val();
        let s = inputContent.length;
        let maxLength=$(item).attr('maxLength');
        document.getElementById(labelName).innerText=`${s}/${maxLength}`;
        return false;
    };
    function openModal() {
        event.preventDefault();
        $('#generalModal')
            .modal({
                blurring: false,
                observeChanges: true,
                transition: "scale",
                onVisible: function (callback) {
                    callback = $.isFunction(callback)
                        ? callback
                        : function () {};
                },
            })
            .modal("show");
    }

    $(document).on("click", ".btnModalOpen", function (event) {
        event.preventDefault();
        let href = $(this).attr("href");
        $.ajax({
            url: href,
            success: function (responseContent) {
              $('#generalModal').find(".content").html(responseContent);
            },
          });
       openModal();
    });

    $(document).on("click", ".btnConfirmModalOpen", function (event) {
        event.preventDefault();
        let href = $(this).attr("href");
        $("#confirmDeleteForm").attr("action", href);
        $("#confirmModal").modal("setting", "closable", false).modal("show");
    });
    $(document).on('keyup keydown keypress change', "#image_url", function (event) {
            imageValue = $(this).val();
           $("#icon_img").attr("src",imageValue);
        });
});
