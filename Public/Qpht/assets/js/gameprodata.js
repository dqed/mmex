var gameprodata=(function($){
	var init=function(){
		var ctx = document.getElementById('myChart').getContext('2d');
		var chart = new Chart(ctx, {
		    // 要创建的图表类型
		    type: 'line',

		    // 数据集
		    data: {
		        labels: ["January", "February", "March", "April", "May", "June", "July"],
		        datasets: [{
		            label: "My First dataset",
		            backgroundColor: 'rgb(255, 99, 132)',
		            borderColor: 'rgb(255, 99, 132)',
		            data: [0, 10, 5, 2, 20, 30, 45],
		        }]
		    },

		    // 配置项
		    options: {}
				});
			}

	init();
})($)