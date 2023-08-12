import Swal from "sweetalert2";

export function Alert({title, text, icon}) {
  Swal.fire({
    title: title,
    text: text,
    icon: icon,
    confirmButtonText: 'OK',
  });
}

export function toastAlert({title, text, icon}) {
  Swal.fire({
    title: title,
    text: text,
    icon: icon,
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });
}

export function showLoading(text) {
  Swal.fire({
    title: 'Please wait...',
    html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div> <br> <br>' + text + '<br><p class="text-danger" style="font-size: 10px;">Don\'t refresh this page!.</p>',
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    onBeforeOpen: () => {
      Swal.showLoading();
    },
  });
}