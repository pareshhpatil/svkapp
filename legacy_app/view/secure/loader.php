<div class="lds-css ng-scope" style="text-align: -webkit-center;text-align: -moz-center;margin-top: 18%;">
<div class="lds-spinner" style="100%;height:100%"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
<style type="text/css">
    @keyframes lds-spinner {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
@-webkit-keyframes lds-spinner {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
.lds-spinner {
  position: relative;
}
.lds-spinner div {
  left: 94px;
  top: 48px;
  position: absolute;
  -webkit-animation: lds-spinner linear 1.8s infinite;
  animation: lds-spinner linear 1.8s infinite;
  background: #18aebf;
  width: 12px;
  height: 24px;
  border-radius: 40%;
  -webkit-transform-origin: 6px 52px;
  transform-origin: 6px 52px;
}
.lds-spinner div:nth-child(1) {
  -webkit-transform: rotate(0deg);
  transform: rotate(0deg);
  -webkit-animation-delay: -1.65s;
  animation-delay: -1.65s;
}
.lds-spinner div:nth-child(2) {
  -webkit-transform: rotate(30deg);
  transform: rotate(30deg);
  -webkit-animation-delay: -1.5s;
  animation-delay: -1.5s;
}
.lds-spinner div:nth-child(3) {
  -webkit-transform: rotate(60deg);
  transform: rotate(60deg);
  -webkit-animation-delay: -1.35s;
  animation-delay: -1.35s;
}
.lds-spinner div:nth-child(4) {
  -webkit-transform: rotate(90deg);
  transform: rotate(90deg);
  -webkit-animation-delay: -1.2s;
  animation-delay: -1.2s;
}
.lds-spinner div:nth-child(5) {
  -webkit-transform: rotate(120deg);
  transform: rotate(120deg);
  -webkit-animation-delay: -1.05s;
  animation-delay: -1.05s;
}
.lds-spinner div:nth-child(6) {
  -webkit-transform: rotate(150deg);
  transform: rotate(150deg);
  -webkit-animation-delay: -0.9s;
  animation-delay: -0.9s;
}
.lds-spinner div:nth-child(7) {
  -webkit-transform: rotate(180deg);
  transform: rotate(180deg);
  -webkit-animation-delay: -0.75s;
  animation-delay: -0.75s;
}
.lds-spinner div:nth-child(8) {
  -webkit-transform: rotate(210deg);
  transform: rotate(210deg);
  -webkit-animation-delay: -0.6s;
  animation-delay: -0.6s;
}
.lds-spinner div:nth-child(9) {
  -webkit-transform: rotate(240deg);
  transform: rotate(240deg);
  -webkit-animation-delay: -0.45s;
  animation-delay: -0.45s;
}
.lds-spinner div:nth-child(10) {
  -webkit-transform: rotate(270deg);
  transform: rotate(270deg);
  -webkit-animation-delay: -0.3s;
  animation-delay: -0.3s;
}
.lds-spinner div:nth-child(11) {
  -webkit-transform: rotate(300deg);
  transform: rotate(300deg);
  -webkit-animation-delay: -0.15s;
  animation-delay: -0.15s;
}
.lds-spinner div:nth-child(12) {
  -webkit-transform: rotate(330deg);
  transform: rotate(330deg);
  -webkit-animation-delay: 0s;
  animation-delay: 0s;
}
.lds-spinner {
  width: 100px !important;
  height: 100px !important;
  -webkit-transform: translate(-50px, -50px) scale(0.5) translate(50px, 50px);
  transform: translate(-50px, -50px) scale(0.5) translate(50px, 50px);
}
</style>
<div style="font-size: 15px;
    line-height: 20px!important;
    font: normal 15px 'Open Sans',arial,sans-serif;
    color: #8b989e!important;">
    <?php if(isset($this->loader_message)){ echo $this->loader_message; } else{?>
    <p>You will be redirected to your merchant website. It might take a few seconds.</p>
<p>Please do not refresh the page or click the "Back" or "Close" button of your browser</p>
    <?php } ?>
</div>
</div>
