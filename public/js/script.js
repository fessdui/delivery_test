$( document ).ready(function() {

  $('#create-schedule').on('submit', function(event) {
    event.preventDefault();

    $.post('/saveSchedule', $(this).serialize(), function(data) {
      var info = $('.info');
      var text  = data.result;
      text += data.estimate_arrival_time;
      info.text(text);
      info.show();
    });
  });


  $('#region').on('change', function(event){
    event.preventDefault();
    setTimeout(function(){
      $('.form-control').prop('disabled', true);
    },300);
    $.ajax({
      type: 'POST',
      url: '/checkfreecourier',
      data: $('#create-schedule').serialize(),
      success: function(data) {
        if (!data.error) {
          var info = $('.info');
          var text  = 'Расчитано время прибытия курьера в Регион: ';
          text += data.estimate_arrival_time;
          info.text(text);
          info.show();
          var couriers = JSON.parse(data.couriers);
          var options = optionsFromCouriers(couriers);
          $("#courier").replaceOptions(options);
        }

        $('.form-control').prop('disabled', false);
      },
      error:  function(xhr, str){
        $('.form-control').prop('disabled', false);
        alert('Возникла ошибка: ' + xhr.responseCode);
      }
    });
  });

  $('#datetimepicker1').datetimepicker({
    date: moment(),
  });

  $('#datetimepicker2').datetimepicker({
    date: moment(),
  });
  $('#datetimepicker3').datetimepicker({
    date: moment(),
  });

  function optionsFromCouriers(couriers){
    var options = [];
    $.each(couriers, function(index, option) {
      options.push({text: option.name + ' ' + option.surname, value: option.id});
    });

    return options;
  }
});

(function($, window) {
  $.fn.replaceOptions = function(options) {
    var self, $option;

    this.empty();
    self = this;

    $.each(options, function(index, option) {
      $option = $("<option></option>")
      .attr("value", option.value)
      .text(option.text);
      self.append($option);
    });
  };
})(jQuery, window);