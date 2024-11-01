document.addEventListener("scroll", miga_sticky_images_scrollHandler);

var pinImages = [];
var oldPos = 0;
var scrollDown = true;
const getOffsetLeft = function (element) {
	if (!element) return 0;
	return getOffsetLeft(element.offsetParent) + element.offsetLeft;
};


function initPins() {
	var pinImageContainer = document.querySelectorAll(".miga_sticky_images__container");
	for (var j = 0; j < pinImageContainer.length; ++j) {
		var pimage = pinImageContainer[j].querySelectorAll(".miga_sticky_images__image");

		for (var i = 0; i < pimage.length; ++i) {
			var pinImage = pimage[i];
			pinImage.querySelector("img").style.width = pinImage.offsetWidth + "px";
			pinImage.style.zIndex = i + 1;
			if (pinImage.classList.contains("onlyOne") && i > 0) {
				pinImage.classList.add("miga_hidden");
			}
			pinImages.push(pinImage);
		}
	}
}

if (document.readyState !== 'loading') {
	miga_sticky_images_eventHandler();
} else {
	document.addEventListener('DOMContentLoaded', miga_sticky_images_eventHandler);
}

function miga_sticky_images_eventHandler() {
	initPins();
}


function miga_sticky_images_scrollHandler() {
	var pos = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
	scrollDown = !(oldPos > pos);
	oldPos = pos;

	for (var i = 0; i < pinImages.length; ++i) {
		var pinImage = pinImages[i];
		var center = (window.innerHeight * 0.5) - (pinImage.getBoundingClientRect().height * 0.5);

		if (pinImage.getBoundingClientRect().top < center) {

			if (pinImage.classList.contains("pinit_last") && pinImage.getBoundingClientRect().top < 0) {
				pinImage.querySelector("img").classList.remove("miga_fixed");
				pinImage.querySelector("img").style.marginTop = center + "px";
			} else {

				if (!pinImage.querySelector("img").classList.contains("miga_fixed")) {

					if (scrollDown) {
						var posLeft = getOffsetLeft(pinImage.querySelector("img"));
						if (pinImage.classList.contains("onlyOne")) {
							if (pinImage.previousElementSibling) pinImage.previousElementSibling.classList.add("miga_hidden");
							pinImage.classList.remove("miga_hidden");
						}
						pinImage.style.height = pinImage.querySelector("img").offsetHeight + "px";
						pinImage.querySelector("img").classList.add("miga_fixed");
						pinImage.querySelector("img").style.top = center + "px";
						pinImage.querySelector("img").style.left = posLeft + "px";
					} else {
						if (pinImage.classList.contains("pinit_last") && pinImage.getBoundingClientRect().top >= 0) {
							pinImage.querySelector("img").style.marginTop = "0";
							pinImage.querySelector("img").classList.add("miga_fixed");
						}
					}
				}
			}
		} else {
			if (!scrollDown) {
				if (i != 0) {
					if (!pinImage.classList.contains("miga_hidden") && pinImage.classList.contains("onlyOne")) {
						pinImage.previousElementSibling.classList.remove("miga_hidden");
						pinImage.classList.add("miga_hidden");
					} else {
						pinImage.querySelector("img").classList.remove("miga_fixed");
					}
				} else {
					pinImage.querySelector("img").classList.remove("miga_fixed");
				}
			} else {
				if (i != 0 && pinImage.classList.contains("onlyOne")) {
					pinImage.classList.add("miga_hidden");
				}
				pinImage.querySelector("img").classList.remove("miga_fixed");
			}
		}
	}
}

miga_sticky_images_scrollHandler();
