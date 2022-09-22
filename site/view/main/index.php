<?php
use App\dto\sectorSubmitForm\SectorSubmitFormDTO;

/* @var SectorSubmitFormDTO $model */

?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport"
         content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.1/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js"></script>

   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"/>
   <link rel="stylesheet" type="text/css" href="../static/css/reset.css"/>
   <link rel="stylesheet" type="text/css" href="../static/css/main-layout.css"/>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>


   <title>Sector Submit Form</title>
</head>
<body class="mx-3">
   <h1 class="h1">Sector Submit Form</h1>

   <form style="display: flex; flex-direction: column">

      <p class="mb-3">Please enter your name and pick the Sectors you are currently involved in.</p>


      <div class="form-group mb-3">
         <label for="form-sectors-name" class="h5">Name:</label>
         <?php
         $prevValue = $model->userSectorsForm === null ? "" : $model->userSectorsForm->getName();
         ?>
         <input type="text" class="form-control" id="form-sectors-name" placeholder="Your name" value='<?php echo $prevValue?>' minlength="3" required >
         <div class="invalid-feedback" id="form-name-invalid-feedback" style="display: none">
            Invalid name!
         </div>
      </div>

      <div class="mb-3">
         <label for="form-sectors" class="h5">Sectors:</label>
         <div style="height: 300px; border: 2px solid #ccc; overflow-y: scroll;" id="form-sectors" class="inherit-margin">
            <?php echo join("", $model->sectorFlatOptionGroupsAndOption); ?>
         </div>
         <div class="invalid-feedback" id="form-sectors-invalid-feedback" style="display: none">
            Select one or more sectors!
         </div>
      </div>

      <div class="form-check mb-3">
         <?php
         $isChecked = $model->userSectorsForm === null ? "" : $model->userSectorsForm->getIsAgreedToTerms();
         $checked = $isChecked ? "checked" : "";
         ?>
         <input type="checkbox" class="form-check-input" id="agreeToTerms" <?php echo $checked ?> required>
         <label class="form-check-label" for="agreeToTerms">Agree to Terms</label>
         <div class="invalid-feedback" id="form-terms-invalid-feedback" style="display: none">
            You must agree to the terms before submitting.
         </div>
      </div>

      <button type="submit" class="btn btn-primary w-25" id="form-submit-btn">Submit</button>
   </form>
</body>

<script type="module">
   document.querySelector('#form-submit-btn').addEventListener('click', onSubmit);

   async function onSubmit(event) {
      event.preventDefault();
      let sectors = Array.from(document.querySelectorAll('#form-sectors input'));
      let checkedSectors = sectors.filter(x => x.checked);
      let sectorIds = checkedSectors.map(x => x.value);


      document.querySelector('#form-sectors-invalid-feedback').style.display = sectorIds.length === 0 ? 'block' : 'none';
      let name = document.querySelector('#form-sectors-name').value;
      let isAgreedToTerms = document.querySelector('#agreeToTerms').checked;
      document.querySelector('#form-terms-invalid-feedback').style.display = !isAgreedToTerms ? 'block' : 'none';
      document.querySelector('#form-name-invalid-feedback').style.display = name.length < 3 ? 'block' : 'none';


      let isValid = sectorIds.length > 0 && name.length >= 3 && isAgreedToTerms;
      if (! isValid) {
         return
      }

      let url = window.location.origin + "/site/viewController/forms/SectorSubmit.php?formId=<?php echo $model->formId ?>";

      postToUrl(url, {
         name: name,
         sectors: JSON.stringify(sectorIds),
         isAgreedToTerms: isAgreedToTerms
      }, 'post');
   }

   function postToUrl(path, params, method) {
      method = method || "post";

      let form = document.createElement("form");
      form.setAttribute("method", method);
      form.setAttribute("action", path);

      for(let key in params) {
         if(params.hasOwnProperty(key)) {
            let hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
      }

      document.body.appendChild(form);
      form.submit();
   }

</script>

</html>



