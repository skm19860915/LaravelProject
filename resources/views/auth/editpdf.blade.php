<div id="form_editor"></div>
@csrf
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/WebViewer/lib/webviewer.min.js')}}"></script>
<script src="{{ asset('assets/WebViewer/samples/old-browser-checker.js')}}"></script>
@if(Auth::user()->role_id == 1)
	<!-- <script src="{{ asset('assets/WebViewer/samples/forms/form-fields/form-fields2.js')}}?v=<?php echo rand(); ?>"></script> -->
@elseif(Auth::user()->role_id == 2)
	<!-- <script src="{{ asset('assets/WebViewer/samples/forms/form-fields/form-fields1.js')}}?v=<?php echo rand(); ?>"></script> -->
@else
	<!-- <script src="{{ asset('assets/WebViewer/samples/forms/form-fields/form-fields.js')}}?v=<?php echo rand(); ?>"></script> -->
@endif
<script type="text/javascript" attr="">
$(document).ready(function(){
    
	setTimeout(function(){
	    window.cdata = <?php echo $client_information_forms; ?>;
	    window.PDFDATA = <?php echo $client_information_forms->information; ?>;
	    window.pdfUrl = "{{asset('storage/app')}}/{{$client_information_forms->file}}";
	    window.id = "{{$client_information_forms->info_id}}";
	    window.token = document.querySelector('input[name="_token"]').value;
	    <?php if(Auth::user()->role_id == 1) { ?>
	    	window.link = "{{url('admin/allcases/updatecaseforms')}}";
	    <?php } else if(Auth::user()->role_id == 2) { ?>
	    	window.link = "{{url('admin/userclient/updateforms')}}";
	    <?php } else { ?>
	    	window.link = "{{url('firm/forms/information_update')}}";
		<?php } ?>
	    document.getElementById('form_editor').style.display = 'block';
	    // WebViewer.getInstance().loadDocument(pdfUrl);  
		var lkey = "Tila Case Prep, Inc.:OEM:TilaCasePrep::B+:AMS(20210406):1FB51A520467460AB360B13AC9A2737860610FAB1C084ACA95952B84BD429BF65A8A31F5C7";
		WebViewer(
		  {
		    path: '../../../lib',
		    licenseKey: lkey,
		    initialDoc: window.pdfUrl,
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
		        }
		      }
		    }   
		    else {
		      if(window.cdata) {
		        var cdata1 = JSON.parse(window.cdata);
		        
		        if(name.toLowerCase().indexOf('mobile') > 0) {
		            field.setValue(cdata1.cell_phone);
		        }
		        
		        for (x in cdata1) {
		          v = cdata1[x];
		          x = x.replace(/_/g, "").toLowerCase();
		          var nn = name.toLowerCase().indexOf(x.toLowerCase());
		          if(nn > 0 && x != 'id') {
		            field.setValue(v);
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
		          save_btn.style = 'width: 55px;display:block;padding: 9px 0;background: #91476a;border-radius: 4px;border: 1px solid #91476a;text-align: center;color: #fff;font-weight: 700;cursor: pointer;';
		          save_btn.className = 'btn btn-primary';
		          save_btn.onclick = () => {
		            const fieldManager = annotManager.getFieldManager();
		            fieldManager.forEachField(getFieldNameAndValue);
		            var formFieldValues = field_data;
		            var domainname = window.location.origin;
		            var link = window.link;
		            data = "data="+JSON.stringify(formFieldValues);
		            data += "&file="+window.pdfUrl;
		            data += "&id="+window.id;
		            data += "&_token="+window.token;
		            var xhttp1 = new XMLHttpRequest();
		            xhttp1.onreadystatechange = function() {
		              if (this.readyState == 4 && this.status == 200) {
		                // var res = JSON.parse(this.responseText);
		                var w_u = window.location.href.split('#');
		                console.log(w_u[0]);
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
		          close_btn.style = 'width: 40px;display:block;padding: 9px 0;background: #91476a;border-radius: 4px;border: 1px solid #91476a;text-align: center;color: #fff;font-weight: 700;cursor: pointer;margin: 0 12px;';
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
		  });
		});
 
	}, 1000);
});
</script>
