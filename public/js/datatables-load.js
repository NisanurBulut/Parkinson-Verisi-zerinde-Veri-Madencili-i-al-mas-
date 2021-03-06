
$('#dtDemand').ready(function () {
  $.ajax({
    url: 'demands/getDemands',
    contentType: 'application/json; charset=utf-8',
    success: function (data) {
      loadDemandsToTable(JSON.parse(data));
    },
  });
});

var languageOption={
  "sDecimal": ",",
  "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
  "sInfo": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
  "sInfoEmpty": "Kayıt yok",
  "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
  "sInfoThousands": ".",
  "sLengthMenu": "Sayfada _MENU_ kayıt göster",
  "sLoadingRecords": "Yükleniyor...",
  "sProcessing": "İşleniyor...",
  "sSearch": "Ara:",
  "sZeroRecords": "Eşleşen kayıt bulunamadı",
  "oPaginate": {
    "sFirst": "İlk",
    "sLast": "Son",
    "sNext": "Sonraki",
    "sPrevious": "Önceki"
  },
  "oAria": {
    "sSortAscending": ": artan sütun sıralamasını aktifleştir",
    "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
  },
  "select": {
    "rows": {
      "1": "1 kayıt seçildi",
      "_": "%d kayıt seçildi"
    }
  }
};
function loadDemandsToTable(dataSource) {
  var table = $('#dtDemand').DataTable({
    data: dataSource,
    language:languageOption,
    columns: [
      {
        data:'appName',
        render: function (data, type, full) {
          return `<div class="ui label">
                        <i class="ui icon rocket violet"></i>
                        ${data}
                      </div>`;
        },
      },
      {
        data: 'title'
      },
      {
        data: 'ownerUsername',
        render: function (data, type, full) {
          return `<a class="ui label" data-tooltip="${full.ownerNamesurname}">
                        <img class="ui right spaced avatar image" src="${full.ownerImageUrl}">
                        ${data}
                      </a>`;
        },
      },
      {
        data: 'takedUsername',
        render: function (data, type, full) {
          return `<a class="ui label" data-tooltip="${full.takedNamesurname}">
                        <img class="ui right spaced avatar image" src="${full.takedImageUrl}">
                        ${data}
                      </a>`;
        },
      },
      {
        data: 'status',
        render: function (data, type, full) {
          return `<div class="ui label ${full.color}">
                        <i class="clock icon"></i> ${data}
                      </div>`;
        },
      },
      {
        data: 'state',
        render: function (data, type, full) {
          return `<p class="ui ${full.color} basic label">${data}</p>`;
        },
      },
      {
        data: 'demandDatetime',
        render: function (data, type, full) {
          return `<div class="ui label">
                        <i class="calender alternate icon"></i> ${data}
                      </div>`;
        },
      },
      {
        data: 'id',
        render: function (data, type) {
          return (
            `<a class="btnModalOpen" data-tooltip="Talebi düzenle" id='${data}' href="/demands/editDemand?id=${data}">` +
            `<i class="blue edit icon"></i></a>` +
            `<a class="btnConfirmModalOpen" data-tooltip="Talebi sil" id='${data}' href="/demands/destroyDemand?id=${data}">` +
            `<i class="red trash icon"></i></a>`+
            `<a class="btnModalOpen" data-tooltip="Talebi görüntüle" id='${data}' href="/demands/showDemand?id=${data}">` +
            `<i class="green gem icon"></i></a>`
          );
        },
      },
    ],
  });
}
