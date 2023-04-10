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
	    window.pdfUrl = "{{asset('storage/app/forms/all')}}/{{$form_name}}";
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
		    initialDoc: window.pdfUrl
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
		      annot.fieldFlags.set('ReadOnly', true);
		    });
		  }

          instance.setHeaderItems(header => {
		    header.delete('1');
		  });
          instance.disableElements([ 'leftPanel', 'leftPanelButton' ]);
          instance.disableElements([ 'rightPanel', 'rightPanelButton' ]);
      
          instance.disableTools();

		
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
