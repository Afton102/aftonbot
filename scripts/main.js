//document.querySelector('html').onclick = function() {
  //  alert('Click!');
//}

document.getElementById("btn").onclick = someFunc;
function getAlert(ex) {
  if(ex){
  alert('Hello!');
  return "ok";
  }
  else {
  alert('Github only one!');
  return "ok";
  }
}
function multiply(num1,num2) {
  var result = num1 * num2;
  return result;
}
function someFunc(){
var nick=document.getElementById('txt1').value;
nick+=' ';
nick+=document.getElementById('txt2').value;	
alert("Здравствуй "+nick);
}
/*class Cookie {
	constructor(name, value, options) {
	this.name = name;
	this.options = options;
	this.value = value;
	}

	get() {
		const matches = document.cookie.match(new RegExp("(?:^|; )" + this.name.replace(/([.$?*|{}()\[\]\\\/+^])/g, '\\$1') + "=([^;]*)"));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}

	exist() {
		return !!this.get();
	}

	set() {
		let options = this.options,
		expires = options.expires;
	
		if (expires && typeof expires === "number") {
		const d = new Date();
		d.setTime(d.getTime() + expires * 1000);
		expires = options.expires = d;
		}
		if (expires && expires.toUTCString) {
		options.expires = expires.toUTCString();
		}

		let value = encodeURIComponent(this.value);

		let updatedCookie = this.name + "=" + value;

		for (const propName in options) {
		updatedCookie += "; " + propName;
		const propValue = options[propName];
		if (propValue !== true) {
		updatedCookie += "=" + propValue;
		}
		}

		document.cookie = updatedCookie;
	}

	destroy() {
		this.options = {expires: -1};
		this.value = "";
		this.set();
	}
}*/
