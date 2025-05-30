(function($){

	// Количество секунд в каждом временном отрезке
	var days	= 24*60*60,
		hours	= 60*60,
		minutes	= 60;

	// Создаем плагин
	$.fn.countdown = function(prop){

		var options = $.extend({
			callback	: function(){},
			timestamp	: 0
		},prop);

		var left, d, h, m, s, positions;

		// инициализируем плагин
		init(this, options);

		positions = this.find('.position');

		(function tick(){

			// Осталось времени
			left = Math.floor((options.timestamp - (new Date())) / 1000);

			if(left < 0){
				left = 0;
			}

			// Осталось дней
			d = Math.floor(left / days);
			updateDuo(0, 1, d);
			left -= d*days;

			// Осталось часов
			h = Math.floor(left / hours);
			updateDuo(2, 3, h);
			left -= h*hours;

			// Осталось минут
			m = Math.floor(left / minutes);
			updateDuo(4, 5, m);
			left -= m*minutes;

			// Осталось секунд
			s = left;
			updateDuo(6, 7, s);

			// Вызываем возвратную функцию пользователя
			options.callback(d, h, m, s);

			// Планируем следующий вызов данной функции через 1 секунду
			setTimeout(tick, 1000);
		})();

		// Данная функция обновляет две цифоровые позиции за один раз
		function updateDuo(minor,major,value){
			switchDigit(positions.eq(minor),Math.floor(value/10)%10);
			switchDigit(positions.eq(major),value%10);
		}

		return this;
	};




	// Создаем анимированный переход между двумя цифрами
	function switchDigit(position,number){

		var digit = position.find('.digit')

		if(digit.is(':animated')){
			return false;
		}

		if(position.data('digit') == number){
			// Мы уже вывели данную цифру
			return false;
		}

		position.data('digit', number);

		var replacement = $('<span>',{
			'class':'digit',
			css:{

			},
			html:number
		});

		// Класс .static добавляется, когда завершается анимация.
		// Выполнение идет более плавно.

		digit
			.before(replacement)
			.removeClass('static')
			.animate({},'fast',function(){
				digit.remove();
			})

		replacement
			.delay(100)
			.animate({},'fast',function(){
				replacement.addClass('static');
			});
	}
})(jQuery);

function addCsTimer(){
	$('body .action-timer .timer_inner').each(function () {
		if($(this).children('.countDays').length == 0){
		if($(this).attr('data-date-end')){
			var parts_date = $(this).attr('data-date-end').split('-');
			var ts = new Date(parts_date[0], parts_date[1] - 1, parts_date[2]);
			if((new Date()) > ts){
				ts = (new Date()).getTime() + 10*24*60*60*1000;
			}
			$(this).countdown({timestamp:ts});
		}
		}
	});
}
$(function () {
	addCsTimer();
});