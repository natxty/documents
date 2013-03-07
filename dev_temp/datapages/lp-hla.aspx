<templates:siteheader 
    title="HLA, HLA Typing, Luminex, DNA Typing, HLA-A, HLA-B, HLA-C, HLA-DRB, HLA DRB1, HLA-DQB, Lifecodes"
    pagetitle="HLA Typing" 
    bodyclass="kirGenotyping" 
    heroimage="../im/products/hero_products_HLA.jpg"
    section="products" 
    subsection="lifecodes-molecular"
    subsectionselected = "hla"
    topnavselected="products" 
    leftnavselected="tranplant" 
    breadcrumbs="<li><a href='./'>Products & Services</a></li><li>&rsaquo;</li><li class='last act'><a href=''>HLA Typing</a></li>" 
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
						<h4>THE LIFECODES ADVANTAGE</h4><br />
						<ul class="bulleted">
							<li>Unique probe design minimizes ambiguities</li>
                            <li>Master mix includes primers, dNTPs and PCR buffer, eliminating extra steps and consumables</li>
                            <li>Homogeneous assay eliminates the need for centrifugation and wash steps, saving time and reducing the chance of pipetting errors</li>
                            <li>Analysis software automatically imports data from the Luminex instrument</li>
							
						</ul>
					</div>
					<div class="tab" id="products">
                      <img src="../im/products/transplant-diagnostics/lifecodes-molecular-products/hla-typing/transdi-lmp-hlatype-products-usa.jpg" width="560" height="300" />
                    </div>
					<div class="tab" id="packageInsert">
						<h4>LIFECODES HLA DNA Typing - IVD Use</h4><br>
						<p><a class="pdf" href="http://www.gen-probe.com/pdfs/pi/LC775IVDE.12 - LIFECODES HLA SSO Product Insert_FDA_English.pdf">English</a></p>

						<p>
							<strong>Swedish</strong> <a class="pdf" href="http://www.gen-probe.com/pdfs/MSDS/MSDS%20LM%20DNA%20Kits%20-%20Swedish.pdf">Click to Download</a>
						</p>
						<p>
							<strong>Streptavidin-PE</strong> <a class="pdf" href="http://www.gen-probe.com/pdfs/MSDS/MSDS%20LIFECODES%20SA-PE%20(628511).pdf">Click to Download</a>
						</p>
						<p>
							<strong>Taq Polymerase</strong> <a class="pdf" href="http://www.gen-probe.com/pdfs/MSDS/MSDS%20Taq%20Polymerase.pdf">Click to Download</a>
						</p>
					</div>
					<div class="tab" id="regulatory"><p>HLA-A, HLA-B, HLA-C, HLA-DRB, HLA DRB1 & HLA-DQB products are cleared for In Vitro Diagnostic Use by the FDA. HLA-DQA and HLA-DPB are for Research Use Only in the US.</p>
                    <p>For additional availability in other countries please contact your local sales rep or distributor.</p></div>
                    
                    
										
					<div class="tab" id="lotspecific">
                    
                    <style>
					.gridtable td {
						text-align: left;
						vertical-align: top;
					}
					
					td.td90 {
						width: 90px!important;
					}
					
					td.td60 {
						width: 60px!important;
					}
					
					td.td50 {
						width: 50px!important;
					}
					
					td.td40 {
						width: 40px!important;
					}
					
					td.td30 {
						width: 30px!important;
					}
					</style>
                   <table width="600" border="0" cellspacing="0" cellpadding="0" class="gridtable">
  <tr class="mgmtcreds small">
    <td width="50" class="td50"><p><b>Cat. #</b></p></td>
    <td width="90" class="td90"><p><b>LIFECODES Product</b></p></td>
    <td width="40" class="td40"><p><b>Lot #</b></p></td>

    <td width="30" class="td30"><b>Cert.</b></td>
    <td width="40" class="td40"><b>TT/RS</b></td>
    <td width="50" class="td50"><b>Panel</b></td>
    <td width="60" class="td60"><p><b>Probe Hit Charts</b></p></td>
    <td width="50" class="td50"><b>Core Seq.</b></td>
    <td width="50" class="td50"><b>Exp.</b></td>
  </tr>

  <tr>
    <td class="td50">628410-50</td>
    <td class="td90">HLA-A Typing Kit</td>
    <td class="td40">02179E</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/628410_50_02179E/628410-50 (QC1253IVD.6) - LC HLA-A Kit 02179E (2011-05).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/628410_50_02179E/LC755.10 - LC HLA-A Typing Kit Worksheet - Lot 02179E Threshold Table.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/lot_specific/LC711.24 - LC HLA-A Probe Hit Table DB2_28_0_07218A_02179E_02020C.pdf">2.28.0</a><br />
      <a href="/pdfs/lot_specific/LC711CWD.3 - LC HLA-A (CWD) Probe Hit Table DB2_28_0_07218A_02179E_02020C.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC725.17 - LC A Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2011-05</td>
  </tr>
  <tr>
    <td class="td50">628410-50</td>
    <td class="td90">HLA-A Typing Kit</td>
    <td class="td40">02020C</td>
    <td class="td30"><a href="/pdfs/lot_specific/628410-50 (QC1253IVD.7) - LC HLA-A Kit 02020C (2012-03).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/lot_specific/LC755.10 - LC HLA-A Typing Kit Worksheet - Lot 02020C Threshold Table.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/lot_specific/LC711.25 - LC HLA-A Probe Hit Table DB2_28_0_07218A_02179E_02020C-2.pdf">2.28.0</a><br />
      <a href="/pdfs/lot_specific/LC711CWD.3 - LC HLA-A (CWD) Probe Hit Table DB2_28_0_07218A_02179E_02020C.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC725.17 - LC A Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2012-03</td>
  </tr>
  
  <tr>

    <td class="td50">628459-50</td>
    <td class="td90">HLA-A eRES Typing Kit</td>
    <td class="td40">08060C</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628459-50 (QC1549IVD.0) - LC HLA-A eRES 08060C (2012-03).pdf">CE</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC755.10 - LC HLA-A Typing Kit Worksheet - Lot 02020C Threshold Table.pdf">TT HLA-A</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC1047.1 - LC HLA-A eRES Typing Kit Worksheet - Threshold table Lot 03160B 02020C.pdf">TT eRES</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC711.25 - LC HLA-A Probe Hit Table DB2_28_0_07218A_02179E_02020C.pdf">2.28.0 HLA-A</a><br />
      <a href="/pdfs/lot_specific/LC1048.5 - LC HLA-A eRES Probe Hit Table DB2_28_0 _12029M_03160B-rev.pdf">2.28.0 eRES </a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC725.17 - LC A Core Seq DB_2_28_0.pdf">2.28.0 HLA-A</a><br />
      <a href="/pdfs/lot_specific/LC1139.1 - LC A eRES Core Seq DB_2_28_0.pdf">2.28.0 eRES</a></td>
    <td class="td50">2012-03</td>
  </tr>
  <tr>
    <td class="td50">628510-50</td>
    <td class="td90">HLA-B Typing Kit</td>
    <td class="td40">02169T</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/628510_50_02169T/628510-50 (QC1244IVD.4) - LIFECODES HLA-B Kit lot 02169T (2011-03).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/628510_50_02169T/LC754.9 - LIFECODES HLA-B Typing Kit Worksheet - Threshold Table lot 02169T.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/lot_specific/LC712.29 - LC HLA-B Probe Hit Table DB2_28_0_02169T.pdf">2.28.0</a><br />
      <a href="/pdfs/lot_specific/LC712CWD.5 - LC HLA-B (CWD) Probe Hit Table DB2_28_0_07139A_06080N.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC726.20 - LC B Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2011-03</td>
  </tr>
  <tr>
    <td class="td50">628510-50</td>
    <td class="td90">HLA-B Typing Kit</td>
    <td class="td40">07139A</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/628510_50_07139A/628510-50 (QC1244IVD.5) - LIFECODES HLA-B Kit Lot 07139A (2011-09).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/628510_50_07139A/LC754.10 - LC HLA-B Typing Kit Worksheet - Threshold Table lot 07139A.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/lot_specific/LC712.30 - LC HLA-B Probe Hit Table DB2_28_0_07139A_06080N.pdf">2.28.0</a><br />
      <a href="/pdfs/lot_specific/LC712CWD.5 - LC HLA-B (CWD) Probe Hit Table DB2_28_0_07139A_06080N.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC726.20 - LC B Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2011-09</td>
  </tr>

  <tr>
    <td class="td50">628510-50</td>
    <td class="td90">HLA-B Typing Kit</td>
    <td class="td40">06080N</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628510-50 (QC1244IVD.5) LIFECODES HLA-B Kit Lot 06080N (2012-07).pdf">IVD</a></td>
    <td class="td40"><a href="#">TT</a></td>
    <td class="td50">&nbsp;</td>

    <td class="td60"><a href="/pdfs/lot_specific/LC712.30 - LC HLA-B Probe Hit Table DB2_28_0_07139A_06080N.pdf">2.28.0</a><br />
      <a href="/pdfs/lot_specific/LC712CWD.5 - LC HLA-B (CWD) Probe Hit Table DB2_28_0_07139A_06080N.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC726.20 - LC B Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2012-07</td>
  </tr>
  <tr>
    <td class="td50">628510-50</td>
    <td class="td90">HLA-B Typing Kit</td>

    <td class="td40">11180A</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628510-50 (QC1244IVD.5) - LIFECODES HLA-B Kits Lot 11180A (2013-01).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC754.10 - LC HLA-B Typing Kit Worksheet -Threshold Table lot 11180A.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC712.30 - LC HLA-B Probe Hit Table DB2_28_0_07139A_06080N_11180A.pdf">2.28.0</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC712CWD.5 - LC HLA-B (CWD) Probe Hit Table DB2_28_0_07139A_06080N_11180A.pdf">2.28.0 CWD</a></td>

    <td class="td50"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC726.20 - LC B Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2013-01</td>
  </tr>
  
  <tr>
    <td class="td50">628559-50</td>
    <td class="td90">HLA-B eRES Typing Kit</td>
    <td class="td40">08060B</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628559-50 (QC1550IVD.0) - LC HLA-B eRES 08060B (2012-05).pdf">CE</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC754.10 - LC HLA-B Typing Kit Worksheet -Threshold Table lot 06080N.pdf">TT HLA-B</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC1051.1 - LC HLA-B eRES Typing Kit Worksheet - Threshold Table Lot 04040V 06080N.pdf">TT eRES</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/lot_specific/LC712.30 - LC HLA-B Probe Hit Table DB2_28_0_07139A_06080N.pdf">2.28.0 HLA-B</a><br />
      <a href="/pdfs/lot_specific/LC1052.3 - LC HLA-B eRes Probe Hit Table DB2_28 0_0404V.pdf">2.28.0 eRES</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC726.20 - LC B Core Seq DB_2_28_0.pdf">2.28.0 HLA-B</a><br />
      <a href="/pdfs/lot_specific/LC1138.2 - LC B eRES Core Seq DB_2_28_0.pdf">2.28.0 eRES</a></td>
    <td class="td50">2012-05</td>
  </tr>
  <tr>
    <td class="td50">628559-50</td>
    <td class="td90">HLA-B eRES Typing Kit</td>

    <td class="td40">02011D</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628559-50 (QC1550IVD.0) LIFECODES HLA-B eRES Kit Lot 02011D (2013-01).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC1051.1 - LC HLA-B eRES Typing Kit Worksheet - Threshold Table Lot 11090A 11180A.pdf">TT HLA-B</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC754.10 - LC HLA-B Typing Kit Worksheet -Threshold Table lot 11180A.pdf">TT eRES</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC712.30 - LC HLA-B Probe Hit Table DB2_28_0_07139A_06080N_11180A.pdf">2.28.0 HLA-B</a><br />

      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC1052.3 - LC HLA-B eRes Probe Hit Table DB2_28 0_04040V_11090A.pdf">2.28.0 eRES</a></td>
    <td class="td50"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC726.20 - LC B Core Seq DB_2_28_0.pdf">2.28.0 HLA-B</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC1138.2 - LC B eRES Core Seq DB_2_28_0.pdf">2.28.0 eRES</a></td>
    <td class="td50">2013-01</td>
  </tr>
  <tr>
    <td class="td50">628810-50</td>
    <td class="td90">HLA-C Typing Kit</td>
    <td class="td40">03129A</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/628810_50_03129A/628810-50 (QC1306RUO.6) - LC HLA-C Kit 03129A (2011-03).pdf">RUO</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/628810_50_03129A/628810-50 (QC1306IVD.6) - LC HLA-C Kit 03129A (2011-03).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/628810_50_03129A/LC825.5 - LC HLA-C Typing Kit Worksheet - Threshold Table lot 03129AR.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/lot_specific/LC796.16 - LC HLA-C Probe Hit Table DB2_28_0_06068F_03129A_03160A.pdf">2.28.0</a><br />
      <a href="/pdfs/lot_specific/LC796CWD.3 - LC HLA-C (CWD) Probe Hit Table DB2.28.0_03129A_03160A.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC803.12 - LC C Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2011-03</td>

  </tr>
  <tr>
    <td class="td50">628810-50</td>
    <td class="td90">HLA-C Typing Kit</td>
    <td class="td40">03160A</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/628810_50_03129A/628810-50 (QC1306RUO.6) - LC HLA-C Kit 03129A (2011-03).pdf">RUO</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/628810_50_03129A/628810-50 (QC1306IVD.6) - LC HLA-C Kit 03129A (2011-03).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/628810_50_03129A/LC825.5 - LC HLA-C Typing Kit Worksheet - Threshold Table lot 03129AR.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/lot_specific/LC796.16 - LC HLA-C Probe Hit Table DB2_28_0_06068F_03129A_03160A.pdf">2.28.0</a><br />
      <a href="/pdfs/lot_specific/LC796CWD.3 - LC HLA-C (CWD) Probe Hit Table DB2.28.0_03129A_03160A.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC803.12 - LC C Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2012-04</td>
  </tr>
    <tr>
    <td class="td50">628810-50</td>
    <td class="td90">HLA-C</td>
    <td class="td40">10140D</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628810-50 (QC1306RUO.7) - LC HLA-C Kit Lot 10140D (2012-08).pdf">RUO</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628810-50 (QC1306IVD.7) - LC HLA-C Kit Lot 10140D (2012-08).pdf">IVD</a></td>

    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC825.5 - LC HLA-C Typing Kit Worksheet - Threshold Table Lot 10140D.pdf">RS</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC796.16 - LC HLA-C Probe Hit Table DB2_28_0_03129A_03160A_10140D.pdf">2.28.0</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC796CWD.3 - LC HLA-C (CWD) Probe Hit Table DB2_28_0_03129A_03160A_10140D.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC803.12 - LC C Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2012-08</td>

  </tr>
          <tr>
    <td class="td50">628810-50</td>
    <td class="td90">HLA-C KIT</td>
    <td class="td40">12100B</td>
    <td class="td30"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628810-50 (QC1306RUO.7) - LC HLA-C Kit Lot 12100B (2012-11).pdf">RUO</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/628810-50 (QC1306IVD.7) -LC HLA-C Kit Lot 12100B (2012-11).pdf">IVD</a></td>

    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC825.5 - LC HLA-C Typing Kit Worksheet - Threshold Table Lot 12100B.pdf">RS</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC796.16 - LC HLA-C Probe Hit Table DB2_28_0_03129A_03160A_10140D_12100B.pdf">2.28.0</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC796CWD.3 - LC HLA-C (CWD) Probe Hit Table DB2_28_0_03129A_03160A_10140D_12100B.pdf">2.28.0 CWD</a></td>
    <td class="td50"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC803.12 - LC C Core Seq DB_2_28_0.pdf">2.28.0</a></td>
    <td class="td50">2012-11</td>

  </tr>
  
  
  <tr>
    <td class="td50">628710-50</td>
    <td class="td90">HLA-DRB Typing Kit</td>
    <td class="td40">12028A</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/628710_50_12028A/628710-50%20(QC1278IVD.9)%20-%20LC%20HLA-DRB%20Lot%2012028A%20(2011-02).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/628710_50_12028A/LC690.7%20-%20LC%20HLA-DRB%20Typing%20Kit%20Worksheet%20-%20Threshold%2012028A.pdf">TT</a></td>

    <td class="td50">&nbsp;</td>
    <td class="td60"><p><a href="/pdfs/lot_specific/LC657.21 - LC HLA-DRB Generic Probe Hit Table DB2_28_0_02278U_12028A.pdf" target="_blank">Generic 
    2.28.0</a><br>
    <a href="/pdfs/lot_specific/LC657CWD.2 - LC HLA-DRB (CWD) Generic Probe Hit Table DB2_28_0_12028A.pdf" target="_blank">Generic 
      2.28.0 CWD</a></p>
    <p><a href="/pdfs/lot_specific/LC658CWD.2 - LC HLA-DRB (CWD) DR52 Probe Hit Table DB2_28_0_12028A.pdf" target="_blank">DR52 
      2.28.0<br>
    DR52 2.28.0 CWD</a></p></td>
  <td class="td50"><a href="/pdfs/lot_specific/LC704.17 - LC DRB Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>

  <td class="td50">2011-02</td>
  </tr>
  
  
  <tr>
    <td class="td50">628710-50</td>
    <td class="td90">HLA-DRB KIT</td>
    <td class="td40">09130B</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/628710-50 (QC1278IVD.10) - LC HLA-DRB LOT 09130B (2012-10).pdf">IVD</a></td>

    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC690.7 - LC HLA-DRB Typing Kit Worksheet - Threshold 09130B.pdf">RS</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><p><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC657.22 - LC HLA-DRB Generic Probe Hit Table DB2_28_0_12028A_09130B.pdf" target="_blank">Generic 
    2.28.0</a><br>
    <a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC657CWD.3 - LC HLA-DRB (CWD) Generic Probe Hit Table DB2_28_0_12028A_09130B.pdf" target="_blank">Generic 
      2.28.0 CWD</a></p>
    <p><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC658.19 - LC HLA-DRB DR52 Probe Hit Table DB2_28_0_12028A _09130B.pdf" target="_blank">DR52 
      2.28.0</a><br>
    <a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC658CWD.3 - LC HLA-DRB (CWD) DR52 Probe Hit Table DB2_28_0_12028A_09130B.pdf" target="_blank">DR52 2.28.0 CWD</a></p></td>

  <td class="td50"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC704.17 - LC DRB Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>
  <td class="td50">2012-10</td>
  </tr>
  
  
  <tr>
    <td class="td50">628751-50</td>
    <td class="td90">HLA-DRB1 Typing Kit</td>
    <td class="td40">07209U</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/628751_50_07209U/628751-50%20(QC1338IVD.5)%20-%20LIFECODES%20HLA-DRB1%20Lot%2007209U%20(2011-09.pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/628751_50_07209U/LC833.7%20-%20HLA-DRB1%20Typing%20Kit%20Worksheet%20-%20Threshold%20Table%20Lot%2007209U.pdf">TT</a></td>
    
    <td class="td60">&nbsp;</td>
    <td class="td50"><p><a href="/pdfs/lot_specific/LC830.20 - LC HLA-DRB1 Probe Hit Table DB2_28_0_07209U_02220B.pdf" target="_blank">2.28.0</a><br>
      <a href="/pdfs/lot_specific/LC830CWD.4 - LC HLA-DRB1 (CWD) Probe Hit Table DB2.28.0_07209U_02220B.pdf" target="_blank">2.28.0 
        CWD</a></p></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC899.14 - LC DRB1 Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>
    <td class="td50">2011-09</td>
  </tr>
  
  
  <tr>

    <td class="td50">628751-50</td>
    <td class="td90">HLA-DRB1 Typing Kit</td>
    <td class="td40">02220B</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/628751-50 (QC1338IVD.5) - LIFECODES HLA-DRB1 Lot 02220B (2012-03).pdf">IVD</a></td>
    <td class="td40"><a href="#">TT</a></td>
    <td class="td50">&nbsp;</td>

    <td class="td60"><p><a href="/pdfs/lot_specific/LC830.21 - LC HLA-DRB1 Probe Hit Table DB2_28_0_07209U_02220B-rev.pdf" target="_blank">2.28.0</a><br>
      <a href="/pdfs/lot_specific/LC830CWD.4 - LC HLA-DRB1 (CWD) Probe Hit Table DB2.28.0_07209U_02220B.pdf" target="_blank">2.28.0 
      CWD</a></p></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC899.14 - LC DRB1 Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>
    <td class="td50">2012-03</td>
  </tr>
  
  <tr>
    <td class="td50">628751-50</td>
    <td class="td90">HLA-DRB1 KIT  </td>
    <td class="td40">12130A</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/628751-50 (QC1338IVD.5) - LIFECODES HLA-DRB1 Lot 12130A (2013-02).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC833.7 - HLA-DRB1 Typing Kit Worksheet - Threshold Table Lot 12130A.pdf">RS</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><p><a href="/pdfs/documents//LOT%20SPECIFIC/HLAproducts-lotSpecific/LC830.21 - LC HLA-DRB1 Probe Hit Table DB2_28_0_07209U_02220B_12130A.pdf" target="_blank">2.28.0</a><br>
      <a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC830CWD.4 - LC HLA-DRB1 (CWD) Probe Hit Table DB2.28.0_07209U_02220B_12130A.pdf" target="_blank">2.28.0 
      CWD</a></p></td>
    <td class="td50"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC899.14 - LC DRB1 Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>
    <td class="td50">2013-02</td>
  </tr>
  
  
  <tr>
    <td class="td50">628759-50</td>

    <td class="td90">HLA-DRB1 eRES Typing Kit</td>
    <td class="td40">08060A</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/628759-50 (QC1551IVD.0) - LC HLA-DRB1 eRES 08060A (2012-03).pdf">CE</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC833.7 - HLA-DRB1 Typing Kit Worksheet - Threshold Table Lot 02220B.pdf">TT HLA-DRB1</a><br />
      <a href="/pdfs/documents/LOT SPECIFIC/HLAproducts-lotSpecific/LC1085.0 - HLA-DRB1 eRES Typing Kit Worksheet - Threshold Table Lot 01280Z, 02220B.pdf">TT eRES</a></td>
    <td class="td50">&nbsp;</td>

    <td class="td60"><a href="/pdfs/lot_specific/LC830.20 - LC HLA-DRB1 Probe Hit Table DB2_28_0_07209U_02220B.pdf">2.28.0 HLA-DRB1</a><br />
      <a href="/pdfs/lot_specific/LC1076.3 - LC HLA-DRB1 eRes Probe Hit Table DB1 2_28_0_01280Z_05170A.pdf">2.28.0 eRES</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC899.14 - LC DRB1 Core Seq DB_2_28_0.pdf">2.28.0 HLA-DRB1</a><br />
      <a href="/pdfs/lot_specific/LC1137.1 - LC DRB1eRES Core Seq DB_2_28_0.pdf">2.28.0 eRES</a></td>
    <td class="td50">2012-03</td>
  </tr>

  
  
  <tr>
    <td class="td50">628759-50</td>
    <td class="td90">HLA-DRB1 eRES Typing Kit</td>
    <td class="td40">08130B</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/628759-50 (QC1551IVD.0) - LC HLA-DRB1 eRES 08130B (2012-03).pdf">CE</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/628759-50 (QC1551IVD.0) - LC HLA-DRB1 eRES 08130B (2012-03).pdf">TT HLA-DRB1</a><br />

      <a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC1085.0 - HLA-DRB1 eRES Typing Kit Worksheet - Threshold Table Lot 05170A, 02220B.pdf">TT eRES</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><p><a href="/pdfs/lot_specific/LC830.20 - LC HLA-DRB1 Probe Hit Table DB2.28.0_07209U_02220B.pdf" target="_blank">2.28.0 
      HLA-DRB1</a><br>
      <a href="/pdfs/lot_specific/LC1076.3 - LC HLA-DRB1 eRes Probe Hit Table DB1 2_28_0_01280Z_05170A.pdf" target="_blank">2.28.0 
      eRES</a></p></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC899.14 - LC DRB1 Core Seq DB_2_28_0.pdf" target="_blank">2.28.0 
        HLA-DRB1</a><br>
        <a href="/pdfs/lot_specific/LC1137.1 - LC DRB1eRES Core Seq DB_2_28_0.pdf" target="_blank">2.28.0 
        eRES</a></td>

    <td class="td50">2012-03</td>
  </tr>
  
  <tr>
    <td class="td50">628759-50</td>

    <td class="td90">HLA-DRB1 eRES KIT</td>
    <td class="td40">03091Z</td>
    <td class="td30"><a href="/pdfs/lot_specific/628759-50 (1551IVD.0) - LC HLA-DRB1 eRES 03091Z (2013-02).pdf">CE</a></td>
    <td class="td40"><a href="/pdfs/lot_specific/LC833.7 - HLA-DRB1 Typing Kit Worksheet - Threshold Table Lot 12130A.pdf">TT HLA-DRB-1</a><br />
      <a href="/pdfs/lot_specific/LC1085.1 - HLA-DRB1 eRES Typing Kit Worksheet - Threshold Table Lot 01031A, 12130A.pdf">TT eRES</a></td>
    <td class="td50">&nbsp;</td>

    <td class="td60"><a href="/pdfs/lot_specific/LC830.21 - LC HLA-DRB1 Probe Hit Table DB2_28_0_07209U_02220B_12130A.pdf">2.28.0 HLA-DRB1</a><br />
      <a href="/pdfs/lot_specific/LC1076.3 - LC HLA-DRB1 eRes Probe Hit Table DB1 2_28_0_01280Z_05170A_01031A.pdf">2.28.0 eRES</a></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC899.14 - LC DRB1 Core Seq DB_2_28_0.pdf">2.28.0 HLA-DRB1</a><br />
      <a href="/pdfs/lot_specific/LC1137.1 - LC DRB1eRES Core Seq DB_2_28_0.pdf">2.28.0 eRES</a></td>
    <td class="td50">2013-02</td>
  </tr>
  
  
  <tr>
    <td class="td50">629200-50</td>
    <td class="td90">HLA-DRB 3,4,5 Typing Kit</td>
    <td class="td40">08160A</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/629200-50 (QC1559RUO.0)-LC HLA-DRB 3, 4, 5 Lot 08160A 2012-09).pdf">RUO</a></td>

    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC1043.2 - LC DRB345 Typing Kit Worksheet - Threshold Table Lot 08160A.pdf">TT HLA-DRB 3,4,5</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><p><a href="/pdfs/lot_specific/LC1044.3 - LC DRB 345 Probe Hit Table DB2.28.0_08160A.pdf" target="_blank">2.28.0</a><br>
      <a href="/pdfs/lot_specific/LC1044CWD.3 - LC DRB 345 (CWD Alleles Only) Probe Hit Table DB2_28_0_08160A.pdf" target="_blank">2.28.0 
      CWD </a></p></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC1167.0 - LC DRB345 Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a><br></td>
    <td class="td50">2012-09</td>

  </tr>
  
  <tr>
    <td class="td50">629200-50</td>
    <td class="td90">HLA-DRB3,4,5 KIT  </td>
    <td class="td40">08160A</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/629200-50 (QC1559CE.0) - LC HLA-DRB 3,4,5 Lot 08160A (2012-09).pdf">CE</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC1043.2 - LC DRB345 Typing Kit Worksheet - Threshold Table Lot 08160A.pdf">RS</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><p><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC1044.3 - LC DRB 345 Probe Hit Table DB2.28.0_08160A.pdf" target="_blank">2.28.0</a><br>
      <a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC1044CWD.3 - LC DRB 345 (CWD Alleles Only) Probe Hit Table DB2_28_0_08160A.pdf" target="_blank">2.28.0 
      CWD </a></p></td>
    <td class="td50"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/LC1167.0 - LC DRB345 Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a><br></td>
    <td class="td50">2012-09</td>
  </tr>
  
  
  <tr>
    
    <td class="td50">628610-50</td>
    <td class="td90">HLA-DQB Typing Kit</td>
    <td class="td40">01130Y</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/628610_01130Y/628610-50%20(QC1279IVD.9)%20-%20LC%20HLA-DQB%20Kit%2001130Y%20(2012-02).pdf">IVD</a></td>
    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/628610_01130Y/LC698.11%20-%20LC%20HLA-DQB%20Typing%20Kit%20Worksheet%20-%20Lot%2001130Y.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    
    <td class="td60"><p><a href="/pdfs/lot_specific/LC700.26 - LC HLA-DQB Probe Hit Table DB2.28.0_08088Z_01130Y.pdf" target="_blank">2.28.0</a><br>
      <a href="/pdfs/lot_specific/LC700CWD.5 - LC HLA-DQB (CWD) Probe Hit Table DB2.28.0_08088Z_01130Y.pdf" target="_blank">2.28.0 
        CWD</a></p></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC724.12 - LC DQB Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>
    <td class="td50">2012-02</td>
  </tr>

    <tr>
      <td class="td50">628910</td>
      <td class="td90">HLA-DPB</td>
      <td class="td40">09280A</td>
      <td class="td30"><a href="/pdfs/lot_specific/628910 (QC1356RUO.5) - LIFECODES HLA-DPB Kit 09280A (2012-08).pdf">RUO</a><br />
        <a href="/pdfs/lot_specific/628910 (QC1356IVD.5) - LIFECODES HLA-DPB Kit 09280A (2012-08).pdf">IVD</a></td>
      
      <td class="td40"><a href="/pdfs/lot_specific/LC834.4 - LIFECODES HLA-DPB - Threshold Table lot 09280A.pdf">RS</a></td>
      <td class="td50">&nbsp;</td>
      <td class="td60"><p><a href="/pdfs/lot_specific/LC836.10 - LC HLA-DPB Probe Hit Table DB2_28_0_10078B_09280A.pdf" target="_blank">2.28.0</a><br>
        <a href="/pdfs/lot_specific/LC836CWD.2 - LC HLA-DPB (CWD) Probe Hit Table DB2_28_0_10078B_09280A.pdf" target="_blank">2.28.0 
        CWD</a></p></td>
      <td class="td50"><a href="/pdfs/lot_specific/LC887.7 - LC DPB Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>
      <td class="td50">2012-08</td>
      
    </tr>
  
  
  <tr>
    <td class="td50">628061</td>
    <td class="td90">HLA-DQA Typing Kit</td>
    <td class="td40">05219A</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/628061_05219A/628061%20(QC1366RUO.0)%20-%20LC%20HLA-DQA%20Kit%20LOT%2005219A%20(2011-07).pdf">RUO</a><br />
      <a href="/pdfs/documents/LOT%20SPECIFIC/628061_05219A/628061%20(QC1366IVD.0)%20-%20LC%20HLA-DQA%20Kit%20LOT%2005219A%20(2011-07).pdf">IVD</a></td>
    
    <td class="td40"><a href="/pdfs/documents/LOT%20SPECIFIC/628061_05219A/LC961.1%20-%20LC%20HLA-DQA%20Typing%20Kit%20Worksheet%20-%20Threshold%20Table%20lot%2005219A.pdf">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><p><a href="/pdfs/lot_specific/LC959.9 - LC HLA-DQA Probe Hit Table DB2_28_0_03118A_05219A.pdf" target="_blank">2.28.0</a><br>
      <a href="/pdfs/lot_specific/LC959CWD.2 - LC HLA-DQA (CWD) Probe Hit Table DB2_28_0_05219A_06290A.pdf" target="_blank">2.28.0 
        CWD</a></p></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC958.6 - LC DQA Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>
    <td class="td50">2011-07</td>
    
  </tr>
  
  
  <tr>
    <td class="td50">628061</td>
    <td class="td90">HLA-DQA Typing Kit</td>
    <td class="td40">06290A</td>
    <td class="td30"><a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/628061 (QC1366RUO.1) - LC HLA-DQA Kit Lot 06290A (2012-07).pdf">RUO</a><br />
      <a href="/pdfs/documents/LOT%20SPECIFIC/HLAproducts-lotSpecific/628061 (QC1366RUO.1) - LC HLA-DQA Kit Lot 06290A (2012-07).pdf">IVD</a></td>

    <td class="td40"><a href="#">TT</a></td>
    <td class="td50">&nbsp;</td>
    <td class="td60"><p><a href="/pdfs/lot_specific/LC959.9 - LC HLA-DQA Probe Hit Table DB2_28_0_03118A_05219A_06290A.pdf" target="_blank">2.28.0</a><br>
      <a href="/pdfs/lot_specific/LC959CWD.2 - LC HLA-DQA (CWD) Probe Hit Table DB2_28_0_05219A_06290A.pdf" target="_blank">2.28.0 
      CWD</a></p></td>
    <td class="td50"><a href="/pdfs/lot_specific/LC958.6 - LC DQA Core Seq DB_2_28_0.pdf" target="_blank">2.28.0</a></td>
    <td class="td50">2012-07</td>

  </tr>
  
  

  
  
</table>


                    <blockquote>&nbsp;</blockquote>
        </div>

						<div class="tab" id="softwaredownloads">

							<div id="filebrowser">

							</div>
						</div>
				</div>

<templates:sitefooter runat="server" />