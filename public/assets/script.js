
// function showToast(type, message) {
//     const toastContainer = document.getElementById('toast-container');

//     // Tạo một thẻ div để chứa thông báo
//     const toastElement = document.createElement('div');
//     toastElement.className = `toast ${type}`;
//     toastElement.innerText = message;

//     // Thêm thông báo vào container
//     toastContainer.appendChild(toastElement);

//     // Tự động xóa thông báo sau một khoảng thời gian
//     setTimeout(() => {
//         toastElement.remove();
//     }, 5000); 
// }


// function showToast(type, message) {
//     // Create a div to hold the toast message
//     const toastContainer = document.getElementById('toast-container');
//     const toastElement = document.createElement('div');

//     // Add Bootstrap classes for styling
//     toastElement.className = `toast show ${type}`;

//     // Set the toast content
//     toastElement.innerHTML = `
//         <div class="toast-header">
//             <strong class="me-auto">Thông báo</strong>
//             <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
//         </div>
//         <div class="toast-body">
//             ${message}
//         </div>
//     `;

//     // Append the toast to the container
//     toastContainer.appendChild(toastElement);

//     // Automatically remove the toast after a certain time (e.g., 3 seconds)
//     setTimeout(() => {
//         toastElement.remove();
//     }, 3000);
// }

function toast(options) {
  const toastContainer = document.getElementById('toast');
  const toastElement = document.createElement('div');
  const iconClass = options.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

  toastElement.innerHTML = `
      <div class="toast-header">
        <i class="${iconClass}"></i>
        <strong class="me-auto">${options.title}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        ${options.message}
      </div>
    `;

  toastElement.className = `toast show bg-${options.type} text-light`;

  // Append the toast to the container
  toastContainer.appendChild(toastElement);

  // Automatically remove the toast after the specified duration
  setTimeout(() => {
    toastElement.remove();
  }, options.duration);
}

// Function to show success toast
function showSuccessToast() {
  toast({
    title: "Thành công!",
    message: "Bạn đã thành công.",
    type: "success",
    duration: 5000
  });
}

// Function to show error toast
function showErrorToast() {
  toast({
    title: "Thất bại!",
    message: "Có lỗi xảy ra, vui lòng liên hệ quản trị viên.",
    type: "danger",
    duration: 5000
  });
}


function loadingEffect(state) {
  if (state) {
    $('')
  } else {

  }
}

