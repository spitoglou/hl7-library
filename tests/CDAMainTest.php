<?php

class CDAMainTest extends TestCase
{

    /** @test */
    public function itCanRunPreliminaryTest()
    {
        $handler = new \Spitoglou\HL7\CDAHandler($this->getXML());
        $parsed = $handler->parseCDA();
        print_r($parsed);

    }

    public function getXML()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <ClinicalDocument xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:epsos="urn:epsosorg:ep:medication" xmlns="urn:hl7-org:v3">
		<realmCode code="US"/>
		<typeId extension="POCD_HD000040" root="2.16.840.1.113883.1.3"/>
		<templateId root="1 "/>
		<templateId root="1 "/>
		<id extension="1 " root="1.21"/>
		<code code="57833-6" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" displayName="ePrescription"/>
		<title> </title>
		<effectiveTime xsi:type="IVL_TS">
			<low value="1 "/>
			<high value="1 "/>
		</effectiveTime>
		<!-- Στην περίπτωση του eDispensation το στοιχείο effectiveTime έχει την μορφή : -->
		<effectiveTime value="20150416"/>
		<confidentialityCode code="N" codeSystem="2.16.840.1.113883.5.25"/>
		<languageCode code="el"/>
		<recordTarget contextControlCode="OP" typeCode="RCT">
			<patientRole>
				<id extension="123456789" root="1.10.1"/>
				<id extension="2" root="1.10.30.1"/>
				<id extension="Ι.Κ.Α.-E.T.A.M." root="1.10.30.2"/>
				<id extension="3165936" root="1.10.2"/>
				<id extension="Έμμεσος" root="1.20.1"/>
				<id extension="20150513" root="1.30.1"/>
				<id extension="20150913" root="1.30.2"/>
				<addr>
					<streetAddressLine>ΝΕΑ ΔΙΕΥΘΥΝΣΗ</streetAddressLine>
					<city>ΑΘΗΝΑ</city>
					<postalCode>12345</postalCode>
					<state>ΑΤΤΙΚΗΣ</state>
					<country>GR</country>
				</addr>
				<telecom use="MC" value="tel:+2222222222"/>
				<telecom use="HP" value="mailto:testasssb@alon.com3"/>
				<patient>
					<name>
						<given>Σταύρος</given>
						<family>Πιτόγλου</family>
					</name>
					<administrativeGenderCode code="F" codeSystem="2.16.840.1.113883.5.1" displayName="Female"/>
					<birthTime value="19650101"/>
					<raceCode nullFlavor="NA"/>
					<ethnicGroupCode nullFlavor="NA"/>
					<languageCommunication>
						<templateId root="1.3.6.1.4.1.19376.1.5.3.1.2.1"/>
						<languageCode code="el-GR"/>
					</languageCommunication>
				</patient>
			</patientRole>
		</recordTarget>
		<author>
			<functionCode code="221" codeSystem="2.16.840.1.113883.2.9.6.2.7" codeSystemName="ISCO" displayName="Medical Doctors"/>
			<time value="20150422"/>
			<assignedAuthor>
				<id root="2.16.840.1.113883.4.6"/>
				<id extension="123" root="1.18"/>
				<!-- Σε CDA με templateId : 1.3.6.1.4.1.12559.11.10.1.3.1.1.2 (eDispensation) τα παρακάτω στοιχεία δεν εμφανίζονται -->
				<id extension="987654321" root="1.19"/>
				<id extension="456654" root="1.20"/>
				<telecom use="MC" value="tel:+1234567896"/>
				<telecom use="HP" value="mailto:ergasiasdoc@localhost.com"/>
				<assignedPerson classCode="PSN" determinerCode="INSTANCE">
					<name>
						<given>Γιώργος</given>
						<family>Ιατρός</family>
					</name>
				</assignedPerson>
				<representedOrganization classCode="ORG" determinerCode="INSTANCE">
					<id extension=" " root="1.80.1"/>
					<name></name>
					<telecom nullFlavor="NI"/>
					<addr>
						<streetAddressLine nullFlavor="NI"/>
						<city nullFlavor="NI"/>
						<postalCode nullFlavor="NI"/>
						<state nullFlavor="NI"/>
						<country>GR</country>
					</addr>
				</representedOrganization>
			</assignedAuthor>
		</author>
		<custodian typeCode="CST" />
		<legalAuthenticator />
		<componentOf />
		<!-- Στην περίπτωση του ePrescription τα στοιχεία inFulfillmentOf και relatedDocument δεν εμφανίζονται -->
		<inFulfillmentOf/>
		<relatedDocument typeCode="XFRM"/>
		<component>
            <structuredBody>
                <component>
                    <section>
                        <templateId root=\'1.3.6.1.4.1.12559.11.10.1.3.1.2.1\'/>
                        <!-- Το Barcode της Συνταγής -->
                        <id root="" extension=""/>
                        <code code=\'57828-6\' displayName=\'Prescriptions\' codeSystem=\'2.16.840.1.113883.6.1\' codeSystemName=\'LOINC\'/>
                        <title> Συνταγή </title>
                        <text>
                            <list>
                                <item ID="med_barcode_1">2802578001025</item>
                                <item ID="med_barcode_2">2800933610035</item>
                                <item ID="med_notes_1">Σχόλια για το κάθε φάρμακο</item>
                                <item ID="prescription_notes">Σχόλια Γιατρού για την Συνταγή</item>
                            </list>
                        </text>
                        <!-- Το 1ο entry περιέχει τις γενικές πληροφορίες της Συνταγής (Διαγνώσεις, Σχόλια Γιατρού, κ.τ.λ.)-->
                        <entry>
                            <act classCode="INFRM" moodCode="EVN">
                                <id extension="1" root="1.1.3"/>
                                <id extension="3" root="1.1.4"/>
                                <id extension="2" root="1.1.4.1"/>
                                <id extension="5638653563856836" root="1.1.4.2"/>
                                <id extension="20150625" root="1.1.4.3 "/>
                                <id extension="1" root="1.1.3.1"/>
                                <id extension="1" root="1.1.3.2"/>
                                <id extension="test" root="1.1.3.3"/>
                                <id extension="4" root="1.1.6"/>
                                <id extension="0" root="1.4.11"/>
                                <id extension="0" root="1.1.7"/>
                                <id extension="0" root="1.1.8"/>
                                <id extension="0" root="1.1.9"/>
                                <id extension="0" root="1.1.10"/>
                                <id extension="0" root="1.1.11"/>
                                <id extension="0" root="1.1.12"/>
                                <id extension="0" root="1.1.13"/>
                                <id extension="0" root="1.1.14"/>
                                <id extension="0" root="1.1.15"/>
                                <id extension="0" root="1.1.16"/>
                                <id extension="0" root="1.1.17"/>
                                <id extension="1" root="1.1.18"/>
                                <id extension="2" root="1.1.19"/>
                                <id extension="10.0" root="1.1.2.1"/>
                                <id extension="15.0" root="1.1.2.2"/>
                                <id extension="0.0" root="1.1.2.3"/>
                                <id extension="20.0" root="1.1.2.4"/>
                                <id extension="2.32" root="1.1.2.4.1"/>
                                <id extension="0.0" root="1.2.1.4"/>
                                <id extension="1.78" root="1.2.1.5"/>
                                <id extension="1.78" root="1.2.1.6"/>
                                <id extension="20.5" root="1.1.2.5"/>
                                <id extension="0.0" root="1.1.2.6"/>
                                <id extension="20.0" root="1.1.2.7"/>
                                <id extension="2.0" root="1.1.2.8"/>
                                <id extension="20199293991010" root="1.1.20"/>
                                <id extension="0" root="1.1.21"/>
                                <id extension="1" root="1.1.22.2"/>
                                <id extension="1.0" root="1.1.22"/>
                                <id extension="1" root="1.1.22.1"/>
                                <id extension="0" root="1.1.24"/>
                                <id extension="0" root="1.10.4"/>
                                <id extension="0" root="1.10.9"/>
                                <id extension="0" root="1.4.9"/>
                                <id extension="0" root="1.4.10"/>
                                <id extension="3415" root="1.80"/>
                                <code nullFlavor="NA"/>
                                <statusCode code="new"/>
                                <effectiveTime>
                                    <low value="20030101"/>
                                    <high value="20030107"/>
                                </effectiveTime>
                            </act>
                            <entryRelationship typeCode="SUBJ" inversionInd="true">
                                <act classCode="ACT" moodCode="INT">
                                    <templateId root="2.16.840.1.113883.10.20.1.43"/>
                                    <templateId root="1.3.6.1.4.1.19376.1.5.3.1.4.3.1"/>
                                    <code code="FINSTRUCT" codeSystem="1.3.6.1.4.1.19376.1.5.3.2" codeSystemName="IHEActCode"/>
                                    <!--Αναφορά στα σχόλια Συνταγής του Narrative Block που αντιστοιχεί στη τιμή του
                                    /ClinicalDocument/component/structuredBody/component/section/text/list/item/@ID = “prescription_notes” -->
                                    <text>
                                        <reference value="#prescription_notes"/>
                                    </text>
                                    <statusCode code="completed"/>
                                </act>
                            </entryRelationship>
                            <entryRelationship typeCode="RSON">
                                <observation classCode="OBS" moodCode="EVN">
                                <templateId root="2.16.840.1.113883.10.20.1.28"/>
                                <templateId root="1.3.6.1.4.1.19376.1.5.3.1.4.5"/>
                                <code code="282291009" codeSystem="1.3.6.1.4.1.12559.11.10.1.3.1.42.23" codeSystemName="SNOMEDCT" displayName="Diagnosis interpretation"/>
                                <text>Λίθος χοληφόρου πόρου με χολοκυστίτιδα</text>
                                <statusCode code="completed"/>
                                <!--Διάγνωση ICD10 -->
                                <value xsi:type="CD" code="K80.4" codeSystem="1.3.6.1.4.1.12559.11.10.1.3.1.44.2" codeSystemName="ICD10" displayName="Λίθος χοληφόρου πόρου με χολοκυστίτιδα"/>
                                </observation>
                            </entryRelationship>
                        </entry>
                        <!-- Τα υπόλοιπα entries περιέχουν τις γραμμές της Συνταγής -->
                        <!--Γραμμή Συνταγής 1 -->
                        <entry>
                            <!--Απαιτούμενο στοιχείο που υποδηλώνει την εγγραφή της γραμμής της Συνταγής -->
                            <templateId root=\'1.3.6.1.4.1.12559.11.10.1.3.1.3.2\'/>
                            <substanceAdministration />
                        </entry>
                        <!-- Γραμμή Συνταγής 2 -->
                        <entry>
                        <templateId root="1.3.6.1.4.1.12559.11.10.1.3.1.3.2"/>
                        <substanceAdministration classCode="SBADM" moodCode="INT">
                            <templateId root="2.16.840.1.113883.10.20.1.24"/>
                            <templateId root="1.3.6.1.4.1.12559.11.10.1.3.1.3.2"/>
                            <id extension="21899392 " root="1.21.1 "/>
                            <statusCode code="active"/>
                            <effectiveTime xsi:type="IVL_TS">
                                <low nullFlavor="UNK"/>
                                <high nullFlavor="UNK"/>
                            </effectiveTime>
                            <effectiveTime xsi:type="PIVL_TS" operator="A">
                                <period value="12" unit="h"/>
                            </effectiveTime>
                            <doseQuantity>
                                <low value="5" unit="ΔΙΣΚΙΑ_ΔΙΑΣΠ"/>
                                <high value="5" unit="ΔΙΣΚΙΑ_ΔΙΑΣΠ"/>
                            </doseQuantity>
                            <rateQuantity>
                                <low value="16" unit="d"/>
                                <high value="16" unit="d"/>
                            </rateQuantity>
                            <consumable>
                                <manufacturedProduct>
                                    <manufacturedMaterial>
                                    </manufacturedMaterial>
                                </manufacturedProduct>
                            </consumable>
                            <entryRelationship typeCode="COMP">
                                <supply classCode="SPLY" moodCode="RQO">
                                    <independentInd value="false"/>
                                    <quantity value="1" unit="1"/>
                                </supply>
                            </entryRelationship>
                            <entryRelationship typeCode="SUBJ" inversionInd="true">
                                <act classCode="ACT" moodCode="INT">
                                    <templateId root="2.16.840.1.113883.10.20.1.43"/>
                                    <templateId root="1.3.6.1.4.1.19376.1.5.3.1.4.3.1"/>
                                    <code code="FINSTRUCT" codeSystem="1.3.6.1.4.1.19376.1.5.3.2" codeSystemName="IHEActCode"/>
                                    <text>
                                    <reference value="#med_notes_1"/>
                                    </text>
                                    <statusCode code="completed"/>
                                </act>
                            </entryRelationship>
                            <entryRelationship typeCode="REFR">
                                <act classCode="ACT" moodCode="INT">
                                    <id extension="2802673601014" root="1.7.5.1"/>
                                    <id extension="XAGRID CAPS 0,5MG/CAP BTx1VIALx100" root="1.7.5.2"/>
                                    <id extension="420.99" root="1.8.5.1"/>
                                    <id extension="420.99" root="1.8.5.2"/>
                                    <id extension="411.65" root="1.8.5.3"/>
                                    <id extension="365.3" root="1.8.5.4"/>
                                    <id extension="1" root="1.8.5.5"/>
                                    <id extension="1" root="1.9.5.1"/>
                                    <id extension="R" root="1.9.6.1"/>
                                    <code nullFlavor="NA"/>
                                </act>
                            </entryRelationship>
                            <entryRelationship typeCode="SPRT">
                                <act classCode="ACT" moodCode="EVN">
                                    <templateId root="2.16.840.1.113883.10.12.301"/>
                                    <id extension="25" root="1.4.18"/>
                                    <id extension="1" root="1.4.19"/>
                                    <code nullFlavor="NA"/>
                                </act>
                            </entryRelationship>
                            <entryRelationship typeCode="SPRT">
                                <act classCode="ACT" moodCode="EVN">
                                    <id extension="1" root="2.10.8"/>
                                    <id extension="6.34" root="2.10.9"/>
                                    <id extension="7.96" root="2.10.11"/>
                                    <id extension="6.34" root="2.10.10"/>
                                    <id extension="090909090909" root="2.10.12"/>
                                    <code nullFlavor="NA"/>
                                    <effectiveTime value="20150121161246"/>
                                </act>
                            </entryRelationship>
                        </substanceAdministration>
                        </entry>
                    </section>
                </component>
            </structuredBody>
        </component>
	</ClinicalDocument>';
    }

}
