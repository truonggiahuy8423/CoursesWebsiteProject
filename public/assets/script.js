
function toast({ title = "", message = "", type = "info", duration = 3000 }) {
  const main = document.getElementById("toast");
  if (main) {
    const toast = document.createElement("div");

    // Auto remove toast
    const autoRemoveId = setTimeout(function () {
      main.removeChild(toast);
    }, duration + 1000);

    // Remove toast when clicked
    toast.onclick = function (e) {
      if (e.target.closest(".toast__close")) {
        main.removeChild(toast);
        clearTimeout(autoRemoveId);
      }
    };

    const icons = {
      success: "fa-solid fa-circle-check",
      info: "fa-solid fa-circle-info",
      warning: "fa-solid fa-circle-exclamation",
      error: "fa-solid fa-circle-exclamation"
    };
    const icon = icons[type];
    const delay = (duration / 1000).toFixed(2);

    toast.classList.add("toast", `toast--${type}`);
    toast.style.animation = `slideInLeft ease .3s, fadeOut linear 1s ${delay}s forwards`;

    toast.innerHTML = `
      <div class="">
          <i class="toast__icon ${icon}"></i>
      </div>
      <div class="toast__body">
          <h3 class="toast__title">${title}</h3>
          <p class="toast__msg">${message}</p>
      </div>
      <div class="">
          <i class="toast__close fa-solid fa-circle-xmark"></i>
      </div>
    `;
    main.appendChild(toast);
  }
}

function showSuccessToast() {
  toast({
    title: 'Thành công',
    message: 'Bạn đã thành công',
    type: 'success',
    duration: 5000
  });
}

function showInfoToast() {
  toast({
    title: 'Thông tin',
    message: '',
    type: 'info',
    duration: 5000
  });
}

function showWarningToast() {
  toast({
    title: 'Cảnh báo',
    message: '',
    type: 'warning',
    duration: 5000
  });
}

function showErrorToast() {
  toast({
    title: 'Thất bại',
    message: 'Có lỗi xảy ra, vui lòng liên hệ quản trị viên',
    type: 'error',
    duration: 5000
  });
}

function loadingEffect(state) {
  if (state) {
    $('')
  } else {

  }
}

function setDisable() {

}

function loadingEffect(state) {
    if (state) {
        $('body').append("<div class='loading-effect'><i class='fa-solid fa-spinner loading-icon'></i></div>");
    } else {
        $('.loading-effect').remove();
    }
}