

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';

    if (id === 'confirmSelectModal') {

        document.getElementById('moveSelect').value = '';
    }
}


const select = document.getElementById('moveSelect');
const modal = document.getElementById('confirmSelectModal');

select.addEventListener('change', function () {
    if (this.value) {
        modal.style.display = 'flex';
    }
});

function approveSelect(id) {
    document.getElementById(id).style.display = 'none';
}


document.querySelectorAll('.formatBtn2').forEach(button => {
    console.log("hello")
  button.addEventListener('click', () => {
    const format = button.dataset.format;
    const textarea = document.getElementById('postContent2');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selected = textarea.value.substring(start, end);
    
    let formatted;
    if (format === 'b') formatted = `[b]${selected}[/b]`;
    else if (format === 'i') formatted = `[i]${selected}[/i]`;
    else if (format === 'h2') formatted = `[h2]${selected}[/h2]`;
    else if (format === 'h3') formatted = `[h3]${selected}[/h3]`;
    else if (format === 'url') formatted = `[url]${selected}[/url]`;
    else if (format === 'img') formatted = `[img]${selected}[/img]`;
    else if (format === 'hr') formatted = `[hr]`;

    textarea.setRangeText(formatted, start, end, 'end');
  });
});


document.getElementById('uploadImage2').addEventListener('click', () => {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.onchange = (e) => {
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onload = () => {
      const img = document.createElement('img');
      img.src = reader.result;
      img.style.maxWidth = '100px';
      document.getElementById('uploadedImages2').appendChild(img);
    };
    reader.readAsDataURL(file);
  };
  input.click();
});

document.querySelector('.blogForm2').addEventListener('submit', function(e) {
  e.preventDefault();
  const title = document.getElementById('postTitle2').value;
  const content = document.getElementById('postContent2').value;

  // Example AJAX using fetch
  fetch('http://your-ci-endpoint.com/blog/save', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ title, content })
  })
  .then(res => res.json())
  .then(data => alert('Blog saved successfully'))
  .catch(err => alert('Failed to save blog'));
});


