<div id="form_editor"></div>
@csrf
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/WebViewer/lib/webviewer.min.js')}}"></script>
<script src="{{ asset('assets/WebViewer/samples/old-browser-checker.js')}}"></script>
<!-- <script src="{{ asset('assets/WebViewer/samples/forms/form-fields/form-questionnaire.js')}}?v=<?php echo rand(); ?>"></script> -->
<script type="text/javascript" attr="">
$(document).ready(function(){
    
	setTimeout(function(){
	    window.cdata = <?php echo $formdata; ?>;
	    window.PDFDATA = <?php echo $formdata; ?>;
	    window.pdfUrl = "{{$furl}}";
	    window.id = "{{$id}}";
	    window.lang = "{{$lang}}";
	    window.formtype = "{{$formtype}}";
	    window.token = document.querySelector('input[name="_token"]').value;
	    document.getElementById('form_editor').style.display = 'block';
	    // WebViewer.getInstance().loadDocument(pdfUrl);  
	    var lkey = "Tila Case Prep, Inc.:OEM:TilaCasePrep::B+:AMS(20210406):1FB51A520467460AB360B13AC9A2737860610FAB1C084ACA95952B84BD429BF65A8A31F5C7";
		WebViewer(
		  {
		    path: '../../../lib',
		    licenseKey: lkey,
		    initialDoc: "{{$furl}}",
		  },
		  document.getElementById('form_editor')
		).then(function(instance) {
		  var docViewer = instance.docViewer;
		  var annotManager = instance.annotManager;
		  var Annotations = instance.Annotations;
		  var field_data = [];
		  const getFieldNameAndValue = (field) => {
		    const { name, value } = field;
		    field_data.push({name, value});
		    field.children.forEach(getFieldNameAndValue);
		  }
		  const setFieldNameAndValue = (field) => {
		    const { name, value } = field;
		    
		    field.widgets.map(annot => {
		      annot.fieldFlags.set('ReadOnly', false);
		    });
		    if(window.PDFDATA) {
		      var PDFDATA1 = (window.PDFDATA);
		      for (var i = 0; i < PDFDATA1.length; i++) {
		        if(PDFDATA1[i].name == name) {
		            field.setValue(PDFDATA1[i].value);
		            console.log('============== sss', name, PDFDATA1[i].value);
		        }
		      }
		    }   
		    else {
			    if(window.cdata) {
			      var cdata1 = JSON.parse(window.cdata);
			      
			      if(name.toLowerCase().indexOf('mobile') > 0) {
			          field.setValue(cdata1.cell_phone);
			      }
			      
		        if(name.toLowerCase().indexOf('street') > 0) {
		            field.setValue(cdata1.address);
		        }

			      for (x in cdata1) {
			        v = cdata1[x];
			        x = x.replace(/_/g, "").toLowerCase();
			        var nn = name.toLowerCase().indexOf(x.toLowerCase());
			        if(nn > 0 && x != 'id') {
			          field.setValue(v);
			          //console.log(nn, x, name, v);
			        }

			      }
			    }
			}
		    field.children.forEach(setFieldNameAndValue);
		  }
		  instance.setHeaderItems(header => {
		      // header.delete(9);
		      header.push({
		        type: 'customElement',
		          render: () => {
		            const save_btn = document.createElement('a');
		            save_btn.text = 'Save';
		            save_btn.style = 'width: 55px;padding: 9px 0;background: #91476a;border-radius: 4px;border: 1px solid #91476a;text-align: center;color: #fff;font-weight: 700;cursor: pointer;';
		            save_btn.className = 'btn btn-primary';
		            save_btn.onclick = () => {
		              const fieldManager = annotManager.getFieldManager();
		              fieldManager.forEachField(getFieldNameAndValue);
		              var formFieldValues = field_data;
		              var domainname = window.location.origin;
		              var link = domainname+'/update_questionnaire';
		              data = "data="+JSON.stringify(formFieldValues);
		              data += "&file="+window.pdfUrl;
		              data += "&id="+window.id;
		              data += "&lang="+window.lang;
		              data += "&formtype="+window.formtype;
		              data += "&_token="+window.token;
		              var xhttp1 = new XMLHttpRequest();
		              xhttp1.onreadystatechange = function() {
		                if (this.readyState == 4 && this.status == 200) {
		                  // var res = JSON.parse(this.responseText);
		                  var w_u = window.location.href.split('#');
		                  //console.log(w_u[0]);
		                  window.location = w_u[0];  
		                }
		              };
		              xhttp1.open("POST", link, true);
		              xhttp1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		              xhttp1.send(data);
		              setTimeout(function(){
		                var w_u = window.location.href.split('#');
		                //window.location = w_u[0];  
		              },500);
		            }
		            return save_btn;
		          }
		        }, {
		        type: ''
		      });
		      header.push({
		        type: 'customElement',
		          render: () => {
		            const close_btn = document.createElement('a');
		            close_btn.text = 'X';
		            close_btn.style = 'width: 40px;padding: 9px 0;background: #91476a;border-radius: 4px;border: 1px solid #91476a;text-align: center;color: #fff;font-weight: 700;cursor: pointer;margin: 0 12px;';
		            close_btn.className = 'btn btn-primary';
		            close_btn.onclick = () => {
		              window.close();
		            }
		            return close_btn;
		          }
		        }, {
		        type: ''
		      });
		    });
		  docViewer.on('documentLoaded', () => {
		    instance.setFitMode(instance.FitMode.FitWidth);
		    docViewer.getAnnotationsLoadedPromise().then(() => {
		      const fieldManager = annotManager.getFieldManager();
		      fieldManager.forEachField(setFieldNameAndValue);
		    });
		    <?php if(isset($_GET['action']) && $_GET['action'] == 'print') { ?>
		    instance.print();
		    <?php } else if(isset($_GET['action']) && $_GET['action'] == 'download') { ?>
		    instance.downloadPdf({
		        // includeAnnotations: false,
		      });
			<?php } ?>
		  });
		}); 
	}, 1000);
});
</script>
