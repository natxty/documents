<templates:siteheader 
    title="HLA Testing, HLA, HLA Antigens, IgG, LIFECODES DSA"
    pagetitle="Donor Specific Antibody" 
    bodyclass="Donor_SpecAntibody" 
    heroimage="../im/products/hero_products_DonorSpecAnt.jpg"
    section="products" 
    subsection="lifecodes-antibody"
    subsectionselected = "donor"
    topnavselected="products" 
    leftnavselected="tranplant" 
    breadcrumbs="<li><a href='./'>Products & Services</a></li><li>&rsaquo;</li><li class='last act'><a href=''>Donor Specific Antibody</a></li>" 
    javascript="<script type='text/javascript'>$(document).ready(function(){$('#sectnav li').hide();$('#tabs').tabs();});</script>" 
    runat="server" />
    
<script src="/js/filetree/jqueryFileTree.js"></script>
<style>
	@import url('/js/filetree/jqueryFileTree.css');
	
	#filebrowser {
		width: 500px;
		height: 300px;
		margin-left: 30px;
		overflow: scroll;
	}
</style>
<script>
$(document).ready(function() {
	$('#filebrowser').fileTree({ 
		root: '../uploads/File/product/', 
		script: 'jqueryFileTree.php' }, 
		
		function(file) { 
			window.open(file,'_blank'); 
		});
});
</script>

				<div id="tabs">
                    <ul>
                        <li><a href="#about">About</a></li>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#packageInsert">Package Insert</a></li>
                        <li><a href="#msds">MSDS</a></li>
                        <li><a href="#regulatory">Regulatory</a></li>
                        <li><a href="#lotspecific">Lot Specific</a></li>
                        <li><a href="#softwaredownloads">Downloads</a></li>
                    </ul>
					<div class="tab" id="about">
						<h4>LIFECODES DSA Donor Specific Antibody Detection</h4><br />
						<p>
							DSA makes it possible to screen for the presence of antibodies directed against the HLA antigens of the donor. It is ideal for pre- and post-transplant monitoring. 
				    </p>	
						<p>
							Donor lymphocytes are first isolated and lysed. The lysate is then incubated with Class I specific and Class II specific beads. The HLA antigens of the donor are bound to the specific beads via capture monoclonals. The beads are then incubated with the serum and any HLA antibodies against the donor are detected using the Luminex analyzer after addition of anti-human IgG conjugated with PE.
					  </p>
						<ul class="bulleted">
							<li>Detect donor specific HLA IgG antibodies against Class I and/or Class II antigens in a single well</li>
							<li>Freeze donor lysate for future testing</li> 
							<li>No cell viability issues</li> 
							<li>Values mirror Flow Crossmatch Channel Shifts</li>
							<li>Run from 1 to 91 assays at a time</li> 
						</ul>
                        <p>For Research Use only. </p>
					</div>
					<div class="tab" id="products">
		        <img src="../im/products/transplant-diagnostics/lifecodes-anitbody-detction-products/donor-specific-antibody/transdi-adp-donorspecantibody-products.jpg" width="560" height="81" border="0" /></div>
					<div class="tab" id="packageInsert">
						<p>
							<strong>English</strong> <a class="pdf" href="/pdfs/documents/PRODUCT_INSERTS2/LC977RUO.7%20-%20LIFECODES%20DSA%20IFU%20RUO%2019%20Oct%202009.pdf">Click to Download</a>
						</p>
					</div>
					<div class="tab" id="msds">
						<p>
							<strong>English</strong> <a class="pdf" href="/pdfs/MSDS/MSDS DSA 19 October 2009.pdf">Click to Download</a>
						</p>
					</div>
					<div class="tab" id="regulatory">LIFECODES DSA Donor Specific Antibody Detection products are available for Research Use Only.</div>
					<div class="tab" id="lotspecific">
                    
                    <table width="600" border="0" cellspacing="0" cellpadding="0" class="gridtable">
                    <tr class="mgmtcreds small">
    <td width="50" align="left" valign="top" style="width:50px;"><p><b>Cat. #</b></p></td>
    <td width="90" align="left" valign="top" style="width:90px;"><p><b>LIFECODES Product</b></p></td>
    <td width="60" align="left" valign="top" style="width:90px;"><p><b>Lot #</b></p></td>
    <td width="30" align="left" valign="top" style="width:30px;"><b>Cert.</b></td>
    <td width="40" align="left" valign="top" style="width:40px;"><b>TT/RS</b></td>
    <td width="50" align="left" valign="top" style="width:50px;"><b>Panel</b></td>
    <td width="60" align="left" valign="top" style="width:60px;"><p><b>Probe Hit Charts</b></p></td>
    <td width="50" align="left" valign="top" style="width:50px;"><b>Core Seq.</b></td>
    <td width="50" align="left" valign="top" style="width:50px;"><b>Exp.</b></td>
  </tr>
  </table>
                  </div>
					<div class="tab" id="softwaredownloads">
						
						<div id="filebrowser">
							
						</div>
					</div>
				</div>

<templates:sitefooter runat="server" />