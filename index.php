

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<title>DigiSigner API Demo on PHP</title>
<link rel="icon" href="./favicon.ico" type="image/x-icon">
<script>
  function handleFileSelect(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function(event) {
        const pdfData = event.target.result;
        const pdfBase64 = btoa(pdfData);
        displayPDF(pdfBase64);
        document.getElementById('pdfBase64').value = pdfBase64; // Set Base64 value to hidden input field

    };

    reader.readAsBinaryString(file);
  }
  function displayPDF(base64Data) {
    const pdfViewer = document.getElementById('pdfViewer');
    pdfViewer.src = 'data:application/pdf;base64,' + base64Data;
  }

  
function submitForm(event) {
    event.preventDefault();   
    const form = document.getElementById('pdfForm');
    const formData = {
        filetype : 'pdf',
        CertMatching: false,
        llx : document.getElementById('llx').value,
        lly: document.getElementById('lly').value,
        width: document.getElementById('width').value,
        height: document.getElementById('height').value,
        reason: 'testing',
        signlocation: 'Aizawl',
        signerid: "s",
        signpage: document.getElementById('signpage').value,
        filedata: document.getElementById('pdfBase64').value
    };
    const token = 'eyJhbGciO......';

    fetch('https://localhost:63108/route/signdata', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `${token}`
        },
        body: JSON.stringify(formData)

    })
    .then(response => response.json())
    .then(data => {
        if (data.responseCode === 'SIGVAL' && data.filetype === 'pdf') {
            displayPDF(data.responseMsg);
        } else {
          alert(data.responseMsg)
        }
    })
    .catch(error => {
        alert('Error submitting form:', error);
        // Handle error
    });
  }

</script>
</head>
<body>
<?php
    $api_url = 'https://localhost:63108';
    $curl = curl_init($api_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // Set timeout to 10 seconds
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($curl);
    if ($response === false) {
        $error_message = curl_error($curl);
        $result = "$error_message, Please restart the API Client";
    } else {
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status_code == 200) {
            $result = "Connection to API successful!";
            $api_data = json_decode($response, true); // Assuming API response is JSON
        } else {
            $result = "Failed to connect to API. HTTP Status Code: $http_status_code";
        }
    }
    curl_close($curl);


?>
<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="https://digisigner.caisias.com" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="./logo.svg" class="h-8" alt="Flowbite Logo">
      <span class="self-center text-sm md:text-xl font-thin md:font-normal whitespace-nowrap dark:text-white">DigiSigner
        Demo PHP</span>
    </a>
    <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse gap-2">
      <button data-collapse-toggle="navbar-sticky" type="button"
        class="inline-flex items-center p-2 w-10 md:h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-sticky" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>
    </div>
    <div class="items-end justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
      <ul
        class="text-sm flex flex-col p-4 md:p-0 mt-4 font-normal border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
      </ul>
    </div>
  </div>
</nav>
<div class="md:hidden w-full h-screen w-auto items-center text-center mx-4 flex justify-center">
  Please use only Desktop to access this Page.
  The Signer Functionlity will works on PC only
</div>
<div class="p-2 mx-40 mt-8 font-normal text-xs hidden w-full md:flex md:w-auto md:order-1">
  <div class="rounded-lg mt-14">
    <div class="grid grid-cols-12 gap-4">
      <div class="col-span-4 px-2 flex grid items-top justify-center rounded bg-gray-200 h-auto">
        <div>
          <form id="pdfForm" class="pt-6 px-4 w-full"  onsubmit="submitForm(event)">
              <?php 
                if($response === false) {
                  echo "<div class='w-full mb-5 text-red-500 font-medium'>";
                  echo $result;
                  echo "</div>";
                }else{
                  if($http_status_code === 200){
                    echo "<div class='w-full mb-5 text-green-500 font-medium'>";
                    echo $result;
                    echo "</div>";
                  }else{
                    echo "<div class='w-full mb-5 text-red-500 font-medium'>";
                    echo $result;
                    echo "</div>";
                  }
                }
              ?>
            <div class="mb-2 w-full">
              <label class="block mb-2 text-xs font-medium text-gray-900 dark:text-white" for="small_size">Select PDF file</label>
                <input name="pdffile" type="file" id="pdfFile" accept="application/pdf" onchange="handleFileSelect(event)" class="w-full py-0 text-sm text-gray-100 border-2 border-gray-700 rounded-md cursor-pointer bg-gray-700" required>
            </div>
            <input type="hidden" id="pdfBase64" name="pdfBase64">
            <div class="mb-2 grid grid-cols-12 flex gap-2">
              <div class="col-span-12">
                <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Sign on</label>
                <select id="signpage" name="signpage" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                 required>
                  <option value="">Select...</option>
                  <option value="first">First Page</option>
                  <option value="last">Last Page</option>
                  <option value="all">All Page</option>
                </select>
              </div>
            </div>
            <div class="mb-2 grid grid-cols-12 flex gap-2 p-3 bg-gray-700 rounded">
              <div class="col-span-12 grid">
                <h2 class="text-sm font-normal text-white">Signature box location & size</h2>
                <span class="text-xs font-normal text-white">(Position starts from bottom left co-odinates)</span>
              </div>
              <div class="col-span-6">
                <label class="block mb-1 text-xs  text-gray-200 dark:text-white">Bottom X-coordinates</label>
                <input type="number" name="llx" id="llx" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                  placeholder="100" required>
              </div>

              <div class="col-span-6">
                <label class="block mb-1 text-xs text-gray-200 dark:text-white">Bottom Y-coordinates</label>
                <input type="number" name="lly" id="lly" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="100" required>
              </div>
              <div class="col-span-6">
                <label class="block mb-1 text-xs text-gray-200 dark:text-white">Width</label>
                <input type="number" name="width" id="width" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                  placeholder="160" required>
              </div>
              <div class="col-span-6">
                <label class="block mb-1 text-xs text-gray-200 dark:text-white">Height</label>
                <input type="number" name="height" id="height"class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                  placeholder="80" required>
              </div>
            </div>

            <button type="submit" class="text-white  bg-[#2557D6] hover:bg-[#2557D6]/90 focus:ring-4 focus:ring-[#2557D6]/50 focus:outline-none font-normal rounded-md text-xs px-2 pr-4 py-1.5 text-center inline-flex items-center me-2 mb-2 disabled:opacity-50">
              <svg class="w-6 h-6 mr-1" xmlns="http://www.w3.org/2000/svg" xmlns:bx="https://boxy-svg.com"
                viewBox="0 0 500 500">
                <g transform="matrix(1, 0, 0, 1, 0, 1.4210854715202004e-14)">
                  <path
                    style="stroke-linejoin: round; stroke-linecap: round; stroke: rgb(255, 255, 255); fill: rgba(255, 255, 255, 0); stroke-width: 22px;"
                    d="M 258.679 101.459 C 230.046 74.047 272.313 27.404 305.758 59.446 L 390.588 134.43 C 420.891 169.522 374.867 215.534 343.508 179.124 L 257.609 103.142 C 257.609 103.142 213.065 162.461 120.502 191.641 C 109.689 203.345 103.915 255.872 84.648 373.941 C 221.262 349.688 167.706 359.839 249.35 344.499" />
                  <path
                    style="fill: rgb(216, 216, 216); stroke: rgb(248, 248, 248); stroke-linecap: round; stroke-linejoin: round; stroke-width: 22px;"
                    d="M 205.716 262.898 C 147.956 314.555 93.732 361.797 87.595 368.438" />
                  <ellipse style="stroke: rgb(252, 252, 252); fill: rgba(255, 255, 255, 0); stroke-width: 22px;"
                    cx="344.547" cy="291.351" rx="63.286" ry="64.861" />
                  <path style="stroke: rgb(246, 246, 246); stroke-width: 22px; fill: rgb(255, 255, 255);"
                    d="M 307 347.301 C 306.572 357.987 306.882 436.937 306.882 436.937 C 306.882 436.937 345.616 402.112 348.918 401.765 C 352.499 401.389 351.408 401.926 383.587 436.688 C 383.688 369.405 383.904 401.715 383.472 346.689" />
                  <path
                    d="M 644.8 284.425 L 652.073 292.215 L 662.536 290.188 L 663.841 300.766 L 673.498 305.275 L 668.337 314.6 L 673.498 323.925 L 663.841 328.434 L 662.536 339.012 L 652.073 336.985 L 644.8 344.775 L 637.527 336.985 L 627.064 339.012 L 625.759 328.434 L 616.102 323.925 L 621.263 314.6 L 616.102 305.275 L 625.759 300.766 L 627.064 290.188 L 637.527 292.215 Z"
                    style="stroke: rgb(247, 247, 247); stroke-width: 22px;"
                    transform="matrix(-0.856721, 0.515781, -0.515781, -0.856721, 1059.662711, 227.866813)"
                    bx:shape="star 644.8 314.6 30.175 30.175 0.78 10 1@1b7180ba" />
                </g>
              </svg>
              Sign PDF with DSC
            </button>
          </form>
        </div>

      </div>
        <iframe id="pdfViewer" class="rounded border-2 bg-teal-100 border-gray-300 w-full min-w-[800px] h-[600px]"></iframe>
      </div>
    </div>
  </div>
</div>
</body>
</html>
