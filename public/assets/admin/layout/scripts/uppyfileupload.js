
var uppy = Uppy.Core({ 
    autoProceed: false,
    restrictions: {
      //maxFileSize: 1000000,
      maxNumberOfFiles: 5,
      minNumberOfFiles: 1,
      //allowedFileTypes: ['image/*', 'pdf/*']
    } 
  });

  uppy.use(Uppy.Dashboard, {
      target: '#drag-drop-area', 
      inline: true, 
      showLinkToFileUploadResult: true,
      //showProgressDetails: true,
      height: 200,
      maxHeight: 200,
      width: 300,
      maxWidth: 300
  });

  uppy.use(Uppy.XHRUpload, { 
    endpoint: 'http://localhost/test/upload.php',
    method:'post',
    getResponseError (responseText, response) {
      //return new Error(JSON.parse(responseText).message)
      //document.getElementById("error").innerHTML = response.body;
    }
   
  })

  uppy.on('file-added', (file) => {
    // Do something
    console.log('file-added');
  });

  uppy.on('upload', (data) => {
    // Do something
    console.log('Starting upload');
  });
  uppy.on('upload-success', (file, response) => {
    console.log(file.name, response.uploadURL)
    // var img = new Image()
    // img.width = 300
    // img.alt = file.id
    // img.src = response.uploadURL
    // document.body.appendChild(img)
    document.getElementById("error").innerHTML = response.body;
    console.log(response.body);
  });
  uppy.on('complete', (result) => {
    console.log('successful files:', result.successful)
    console.log('failed files:', result.failed)
  });
  uppy.on('error', (error) => {
      console.error(error.stack);
    });
 
