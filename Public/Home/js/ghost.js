/**
* 严格模式
*/
"use strict";

function _classCallCheck(instance, Constructor){ 
	if (!(instance instanceof Constructor)) { 
		throw new TypeError("Cannot call a class as a function"); 
	} 
}

/**
* 常量
*/
var TWO_PI = Math.PI * 2;

// 获取随机数 可为负数
function randomInt(min,max){
	return Math.floor(Math.random() * (max - min + 1)) + min;
}

/**
* 应用类
*/
var App = function(){

	 /**
     * 应用类构造函数
     */
	function App(){
		var _this = this;

		// 不知道什么意思
		 _classCallCheck(this, App);

		// 获取canvas
		this.canvas = document.getElementById("canvas");

		// 获取context
		this.context = this.canvas.getContext("2d");

		// 宽高
		this.width = this.canvas.width = window.innerWidth;
		this.height = this.canvas.height = window.innerHeight;

		// 设置初始的鼠标位置
		this.mousePosition = {
            x: this.width / 2,
            y: this.height / 2
        };

		// 清空
		this.ghosts = [];
		// 幽灵的数量
		this.numberOfGhosts = Math.round((this.width + this.height) / 200);

		// 调整画布的监听器(Listener) 动态填充窗口
		// addEventListener() 方法用于向指定元素添加事件句柄。
		// element.addEventListener(event事件名, function执行的函数, useCapture)
		// true - 事件句柄在捕获阶段执行
		// false- false- 默认。事件句柄在冒泡阶段执行

		// 调整
		window.addEventListener('resize', function () {
            return _this.resizeCanvas();
        },false);

		// 鼠标移动
		window.addEventListener('mousemove',function (e) {
			return _this.mouseMove(e);
		},false);

		// 触屏
		window.addEventListener('touchmove', function (e) {
            return _this.touchMove(e);
        },false);
	}

	/**
	* 调整大小 宽高更改时内容初始化
	*/
	App.prototype.resizeCanvas = function resizeCanvas(){
		this.width = this.canvas.width = window.innerWidth;
		this.height = this.canvas.height = window.innerHeight;
		this.numberOfGhosts = Math.round((this.width + this.height) / 200);

		// 清空之前的容器 并重新填充新的
		this.ghosts = [];
		this.initializeGhosts();
	};

	// 根据屏幕创建幽灵Ghost对象
	// initialize 初始化
	App.prototype.initializeGhosts = function initializeGhosts() {
		for(var i = 0 ;i < this.numberOfGhosts; i++){
			// 实例化幽灵类
			var ghost = new Ghost(randomInt(0,this.width),randomInt(0,this.height),this.context);

			// 初始化幽灵类
			ghost.initialize();

			// 将幽灵类添加至数组
			this.ghosts.push(ghost);
		}
	};

	/**
	*	更新app类和app每个子类
	*	@return void
	*/
	App.prototype.update = function update(){
		for(var i = 0; i < this.ghosts.length; i++){
			this.ghosts[i].update(this.mousePosition);
		}
	};

	/**
	*	显示app类和app每个子类
	*	@return void
	*/
	App.prototype.render = function render(){
		// 清除画布的每个render
		this.context.clearRect(0,0,this.width,this.height);

		// 触发每个子元素的渲染函数
		for(var i =0;i<this.ghosts.length;i++){
			this.ghosts[i].render(this.context);
		}
	};

	/**
	* 每60秒更新一次
	* @return void
	*/
	App.prototype.loop = function loop(){
		var _this2 = this;

		this.update();
		this.render();

		// requestAnimationFrame
		window.requestAnimationFrame(function(){
			return _this2.loop();
		});
	};

	/**
	* 鼠标移动事件，更新 mousePosition
	* @param event - 浏览器mousemove事件对象
	*/

	App.prototype.mouseMove = function mouseMove(event){
		this.mousePosition = {
			x: event.clientX,
			y: event.clientY
		};
	};

	/**
	* 触屏，更新mousePosition
	* @param event - 浏览器touchmove事件对象
	*/

	App.prototype.touchMove = function touchMove(event){
		event.preventDefault();
		this.mousePosition = {
			x: event.touches[0].clientX,
			y: event.touches[0].clientY
		};
	};

	return App;
}();

/**
* 幽灵类
*/
var Ghost = function(){
	/**
	* Ghost构造函数
    * @param x - 水平位置
    * @param y - 垂直位置
    * @param context - 应用程序的canvas对象的context
	*/

	function Ghost(x,y,context){
		_classCallCheck(this,Ghost);

		this.position = new Vector2D(x,y);
		this.handPosition = new Vector2D(x,y);
		this.context = context;

		this.radius = 50;
		// 眼距
		this.eyeDistance = 10;
		this.eyes = [];
		this.bodyBounceAngle = randomInt(0,100);
		this.bounceDistance = 0.5;
		this.bounceSpeed = 0.05;

		this.velocity = new Vector2D(0,0);
		this.velocity.setLength(Math.random() * 2 + 1);
		this.velocity.setAngle(Math.random() * TWO_PI);
	}

	/**
	* 创建2只眼睛
	* @return void
	*/
	Ghost.prototype.initialize = function initialize(){
		this.eyes.push(new Eye(this.position.getX() - this.eyeDistance, this.position.getY() - 10));
        this.eyes.push(new Eye(this.position.getX() + this.eyeDistance, this.position.getY() - 10));
	};

	/**
	* 更新对象位置
	 * @param mousePosition - 用户鼠标的当前位置，x，y
     * @return void
	*/
	Ghost.prototype.update = function update(mousePosition){
		// 随机幽灵的摆动速度
		if(Math.random() < 0.01){
			this.velocity.setLength(Math.random() * 2 + 1);
			this.velocity.setAngle(Math.random() * TWO_PI);
		}

		// 更新幽灵的位置
		// this.position.addTo(this.velocity);

		// 计算幽灵body和手的弹起速度
		var bodyBounce = new Vector2D(0, Math.sin(this.bodyBounceAngle) * this.bounceDistance);
        var handBounce = new Vector2D(0, Math.sin(this.bodyBounceAngle + 10) * this.bounceDistance / 2);
        this.position.addTo(bodyBounce);
        this.handPosition.subtractFrom(handBounce);

        // 计算眼睛的角度 只需算一次
        var dx = mousePosition.x - this.position.getX();
        var dy = mousePosition.y - this.position.getY();
        var angle = Math.atan2(dy, dx);

        // 触发每个子元素的更新
        for(var i = 0 ;i < this.eyes.length;i++){
        	this.eyes[i].update(bodyBounce,angle);
        }

        // 通过添加速度更新反弹角度
        this.bodyBounceAngle += this.bounceSpeed;
	};

	/**
     * 在画布上渲染此Ghost对象
     * @return void
     */
	Ghost.prototype.render = function render(){
		// 幽灵的body
		this.context.fillStyle = "#ffffff";
		this.context.beginPath();
		this.context.arc(this.position.getX(),this.position.getY(),this.radius,0,TWO_PI);
		this.context.fill();

		// 幽灵的左手
		this.context.fillStyle = "#ffffff";
        this.context.beginPath();
        this.context.arc(this.handPosition.getX() - this.radius + 5, this.handPosition.getY() + 10, 10, 0, TWO_PI);
        this.context.fill();

        // 幽灵的右手
        this.context.fillStyle = "#ffffff";
        this.context.beginPath();
        this.context.arc(this.handPosition.getX() + this.radius - 5, this.handPosition.getY() + 10, 10, 0, TWO_PI);
        this.context.fill();

        // 触发子元素的渲染
        for (var i = 0; i < this.eyes.length; i++) {
            this.eyes[i].render(this.context);
        }
	};

	return Ghost;

}();


/*
* 眼睛类
*/

var Eye = function(){
	/**
	* 眼睛类构造函数
	*/

	function Eye(x,y){
		_classCallCheck(this,Eye);

		this.position = new Vector2D(x,y);
		this.irisPosition = new Vector2D(x,y);
		this.moveRadius = 20;
		this.sizeRadius = 5;
	}

	/**
	* 更新此对象的位置
	* @param velocity - 这只眼目前正在移动的速度
    * @param angle - 指向用户鼠标位置的新角度
    * @return void
	*/
	Eye.prototype.update = function update(velocity, angle) {
        this.position.addTo(velocity);

        this.irisPosition.setX(this.position.getX() + Math.cos(angle) * this.moveRadius);
        this.irisPosition.setY(this.position.getY() + Math.sin(angle) * this.moveRadius);
    };

    /**
    * 在画布上渲染这只眼睛
    * @param context - 应用程序的canvas对象的context
    * @return void
    */
    Eye.prototype.render = function render(context) {
        //眼睛
        context.fillStyle = "#000000";
        context.beginPath();
        context.arc(this.irisPosition.getX(), this.irisPosition.getY(), this.sizeRadius, 0, TWO_PI);
        context.fill();
    };

    return Eye;
}();

/**
* Vector2D 类
*/
var Vector2D = function(){
	/**
	* 构造
	*/
	function Vector2D(x,y){
		_classCallCheck(this,Vector2D);

		this._x = x;
		this._y = y;
	}

	/**
     * @param x
     * @return void
     */

    Vector2D.prototype.setX = function setX(x) {
        this._x = x;
    };

    /**
     * @param y
     * @return void
     */

    Vector2D.prototype.setY = function setY(y) {
        this._y = y;
    };

    /**
     * @return {number}
     */

    Vector2D.prototype.getX = function getX() {
        return this._x;
    };

    /**
     * @return {number}
     */

    Vector2D.prototype.getY = function getY() {
        return this._y;
    };

    /**
     * @param angle
     * @return void
     */

    Vector2D.prototype.setAngle = function setAngle(angle) {
        var length = this.getLength();
        this._x = Math.cos(angle) * length;
        this._y = Math.sin(angle) * length;
    };

    /**
     * @return {number}
     */

    Vector2D.prototype.getAngle = function getAngle() {
        return Math.atan2(this._y, this._x);
    };

    /**
     * @param length
     * @return void
     */

    Vector2D.prototype.setLength = function setLength(length) {
        var angle = this.getAngle();
        this._x = Math.cos(angle) * length;
        this._y = Math.sin(angle) * length;
    };

    /**
     * @return {number}
     */

    Vector2D.prototype.getLength = function getLength() {
        return Math.sqrt(this._x * this._x + this._y * this._y);
    };

    /**
     * @param {Vector} v2
     * @return {Vector2D}
     */

    Vector2D.prototype.add = function add(v2) {
        return new Vector2D(this._x + v2.getX(), this._y + v2.getY());
    };

    /**
     * @param {Vector} v2
     * @return {Vector2D}
     */

    Vector2D.prototype.subtract = function subtract(v2) {
        return new Vector2D(this._x - v2.getX(), this._y - v2.getY());
    };

    /**
     * @param value
     * @return {Vector2D}
     */

    Vector2D.prototype.multiply = function multiply(value) {
        return new Vector2D(this._x * value, this._y * value);
    };

    /**
     * @param value
     * @return {Vector2D}
     */

    Vector2D.prototype.divide = function divide(value) {
        return new Vector2D(this._x / value, this._y / value);
    };

    /**
     * @param v2
     * @return void
     */

    Vector2D.prototype.addTo = function addTo(v2) {
        this._x += v2.getX();
        this._y += v2.getY();
    };

    /**
     * @param v2
     * @return void
     */

    Vector2D.prototype.subtractFrom = function subtractFrom(v2) {
        this._x -= v2.getX();
        this._y -= v2.getY();
    };

    /**
     * @param value
     * @return void
     */

    Vector2D.prototype.multiplyBy = function multiplyBy(value) {
        this._x *= value;
        this._y *= value;
    };

    /**
     * @param value
     * @return void
     */

    Vector2D.prototype.divideBy = function divideBy(value) {
        this._x /= value;
        this._y /= value;
    };

    return Vector2D;
}();

window.onload = function(){
	// 实例化
	var app = new App();

	// 初始化
	app.initializeGhosts();

	// 第一次启动循环函数
	app.loop();
};
