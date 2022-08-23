<?xml version="1.0" encoding="UTF-8"?>
<structure version="22" html-doctype="HTML4 Transitional" compatibility-view="IE9" html-outputextent="Complete" relativeto="*SPS" encodinghtml="UTF-8" encodingrtf="ISO-8859-1" encodingpdf="UTF-8" useimportschema="1" embed-images="1" enable-authentic-scripts="1" authentic-scripts-in-debug-mode-external="0" generated-file-location="DEFAULT" ixbrl-version="1.0">
	<parameters/>
	<schemasources>
		<namespaces>
			<nspair prefix="vc" uri="http://www.w3.org/2007/XMLSchema-versioning"/>
		</namespaces>
		<schemasources>
			<xsdschemasource name="XML" main="1" schemafile="lab.xsd" workingxmlfile="lab.xml"/>
		</schemasources>
	</schemasources>
	<modules/>
	<flags>
		<scripts/>
		<mainparts/>
		<globalparts/>
		<designfragments/>
		<pagelayouts/>
		<xpath-functions/>
	</flags>
	<scripts>
		<script language="javascript"/>
	</scripts>
	<script-project>
		<Project version="4" app="AuthenticView"/>
	</script-project>
	<importedxslt/>
	<globalstyles>
		<rules selector="*">
			<media>
				<media value="all"/>
			</media>
			<rule margin="0 auto" max-width="720px"/>
		</rules>
	</globalstyles>
	<mainparts>
		<children>
			<globaltemplate subtype="main" match="/">
				<document-properties/>
				<children>
					<documentsection>
						<properties columncount="1" columngap="0.50in" headerfooterheight="fixed" pagemultiplepages="0" pagenumberingformat="1" pagenumberingstartat="auto" pagestart="next" paperheight="11in" papermarginbottom="0.79in" papermarginfooter="0.30in" papermarginheader="0.30in" papermarginleft="0.60in" papermarginright="0.60in" papermargintop="0.79in" paperwidth="8.50in"/>
						<watermark>
							<image transparency="50" fill-page="1" center-if-not-fill="1"/>
							<text transparency="50"/>
						</watermark>
					</documentsection>
					<template subtype="source" match="XML">
						<children>
							<newline/>
							<tgrid tablegen-filter-periods-to-month="12" tablegen-filter-periods-to-day="31">
								<properties border="0" width="100%"/>
								<children>
									<tgridbody-cols>
										<children>
											<tgridcol/>
										</children>
									</tgridbody-cols>
									<tgridbody-rows>
										<children>
											<tgridrow>
												<children>
													<tgridcell>
														<styles text-align="center"/>
														<children>
															<image>
																<styles height="83px" margin-bottom="0px" width="82px"/>
																<target>
																	<fixtext value="lab_Images\sv_ms_clip0000_image.jpg"/>
																</target>
															</image>
														</children>
													</tgridcell>
												</children>
											</tgridrow>
											<tgridrow>
												<children>
													<tgridcell>
														<styles text-align="center"/>
														<children>
															<text fixtext="МИНИСТЕРСТВО ОБРАЗОВАНИЯ И НАУКИ РОССИЙСКОЙ ФЕДЕРАЦИИ ">
																<styles font-family="Times New Roman" font-size="12pt" font-weight="bold"/>
															</text>
															<newline/>
															<text fixtext="федеральное государственное бюджетное образовательное учреждение">
																<styles color="#000000" font-family="Times New Roman" font-size="12pt"/>
															</text>
															<newline/>
															<text fixtext="высшего профессионального образования">
																<styles color="#000000" font-family="Times New Roman" font-size="12pt"/>
															</text>
															<newline/>
															<text fixtext="«УЛЬЯНОВСКИЙ ГОСУДАРСТВЕННЫЙ ТЕХНИЧЕСКИЙ УНИВЕРСИТЕТ»">
																<styles font-family="Times New Roman" font-size="12pt" font-weight="bold"/>
															</text>
														</children>
													</tgridcell>
												</children>
											</tgridrow>
										</children>
									</tgridbody-rows>
								</children>
								<wizard-data-repeat>
									<children/>
								</wizard-data-repeat>
								<wizard-data-rows>
									<children/>
								</wizard-data-rows>
								<wizard-data-columns>
									<children/>
								</wizard-data-columns>
							</tgrid>
							<paragraph paragraphtag="p">
								<styles letter-spacing="5px" text-align="center"/>
								<children>
									<text fixtext="ПРИКАЗ">
										<styles font-family="Times New Roman" font-size="15pt" font-weight="bold"/>
									</text>
								</children>
							</paragraph>
							<newline/>
							<newline/>
							<tgrid tablegen-filter-periods-to-month="12" tablegen-filter-periods-to-day="31">
								<properties border="0" width="100%"/>
								<children>
									<tgridbody-cols>
										<children>
											<tgridcol/>
											<tgridcol/>
										</children>
									</tgridbody-cols>
									<tgridbody-rows>
										<children>
											<tgridrow>
												<children>
													<tgridcell>
														<styles text-align="left"/>
														<children>
															<text fixtext="   ">
																<styles font-family="Times New Roman" font-size="14pt" text-decoration="underline"/>
															</text>
															<template subtype="element" match="приказ_о_мерах_по_об_исп_ф_з">
																<children>
																	<template subtype="element" match="содержание">
																		<children>
																			<template subtype="element" match="дата">
																				<children>
																					<content subtype="regular">
																						<styles font-family="Times New Roman" text-decoration="underline"/>
																						<format basic-type="xsd" string="DD Month YYYY" datatype="date"/>
																					</content>
																					<text fixtext="г.">
																						<styles font-family="Times New Roman" text-decoration="underline"/>
																					</text>
																				</children>
																				<variables/>
																			</template>
																		</children>
																		<variables/>
																	</template>
																</children>
																<variables/>
															</template>
															<text fixtext="   ">
																<styles font-family="Times New Roman" font-size="14pt" text-decoration="underline"/>
															</text>
															<text fixtext=".">
																<styles color="white" font-family="Times New Roman" font-size="14pt" text-decoration="underline"/>
															</text>
															<newline/>
														</children>
													</tgridcell>
													<tgridcell>
														<styles text-align="right"/>
														<children>
															<text fixtext="№">
																<styles font-family="Times New Roman" font-size="14pt"/>
															</text>
															<text fixtext="    ">
																<styles font-family="Times New Roman" font-size="14pt" text-decoration="underline"/>
															</text>
															<template subtype="element" match="приказ_о_мерах_по_об_исп_ф_з">
																<children>
																	<template subtype="element" match="содержание">
																		<children>
																			<template subtype="element" match="номер">
																				<children>
																					<content subtype="regular">
																						<styles font-family="Times New Roman" text-decoration="underline"/>
																						<format basic-type="xsd" datatype="integer"/>
																					</content>
																				</children>
																				<variables/>
																			</template>
																		</children>
																		<variables/>
																	</template>
																</children>
																<variables/>
															</template>
															<text fixtext="              ">
																<styles font-family="Times New Roman" font-size="14pt" text-decoration="underline"/>
															</text>
															<text fixtext=".">
																<styles color="white" font-family="Times New Roman" font-size="14pt" text-decoration="underline"/>
															</text>
														</children>
													</tgridcell>
												</children>
											</tgridrow>
										</children>
									</tgridbody-rows>
								</children>
								<wizard-data-repeat>
									<children/>
								</wizard-data-repeat>
								<wizard-data-rows>
									<children/>
								</wizard-data-rows>
								<wizard-data-columns>
									<children/>
								</wizard-data-columns>
							</tgrid>
							<newline/>
							<newline/>
							<tgrid tablegen-filter-periods-to-month="12" tablegen-filter-periods-to-day="31">
								<properties border="0" width="100%"/>
								<children>
									<tgridbody-cols>
										<children>
											<tgridcol>
												<styles width="2.80in"/>
											</tgridcol>
											<tgridcol/>
										</children>
									</tgridbody-cols>
									<tgridbody-rows>
										<children>
											<tgridrow>
												<children>
													<tgridcell>
														<styles text-align="left"/>
														<children>
															<paragraph paragraphtag="p">
																<children>
																	<template subtype="element" match="приказ_о_мерах_по_об_исп_ф_з">
																		<children>
																			<template subtype="element" match="содержание">
																				<children>
																					<template subtype="element" match="тема">
																						<children>
																							<content subtype="regular">
																								<styles font-family="Times New Roman" font-weight="bold"/>
																							</content>
																						</children>
																						<variables/>
																					</template>
																				</children>
																				<variables/>
																			</template>
																		</children>
																		<variables/>
																	</template>
																</children>
															</paragraph>
														</children>
													</tgridcell>
													<tgridcell/>
												</children>
											</tgridrow>
										</children>
									</tgridbody-rows>
								</children>
								<wizard-data-repeat>
									<children/>
								</wizard-data-repeat>
								<wizard-data-rows>
									<children/>
								</wizard-data-rows>
								<wizard-data-columns>
									<children/>
								</wizard-data-columns>
							</tgrid>
							<newline/>
							<newline/>
							<newline/>
							<paragraph paragraphtag="p">
								<children>
									<template subtype="element" match="приказ_о_мерах_по_об_исп_ф_з">
										<children>
											<template subtype="element" match="содержание">
												<children>
													<template subtype="element" match="преамбула">
														<children>
															<content subtype="regular">
																<styles font-family="Times New Roman"/>
															</content>
														</children>
														<variables/>
													</template>
												</children>
												<variables/>
											</template>
										</children>
										<variables/>
									</template>
								</children>
							</paragraph>
							<newline/>
							<paragraph paragraphtag="p">
								<styles line-height="15.84px" margin-bottom="10.67px" margin-left="0px" margin-right="0px" margin-top="0px"/>
								<children>
									<text fixtext="ПРИКАЗЫВАЮ:">
										<styles font-family="Times New Roman" font-size="14pt" font-weight="bold"/>
									</text>
								</children>
							</paragraph>
							<tgrid tablegen-filter-periods-to-month="12" tablegen-filter-periods-to-day="31">
								<properties border="0"/>
								<children>
									<tgridbody-cols>
										<children>
											<tgridcol/>
											<tgridcol/>
										</children>
									</tgridbody-cols>
									<tgridbody-rows>
										<children>
											<template subtype="element" match="приказ_о_мерах_по_об_исп_ф_з">
												<children>
													<template subtype="element" match="меры">
														<children>
															<tgridrow>
																<children>
																	<tgridcell>
																		<properties valign="top"/>
																		<children>
																			<template subtype="element" match="номер_п">
																				<children>
																					<content subtype="regular">
																						<format basic-type="xsd" datatype="integer"/>
																					</content>
																				</children>
																				<variables/>
																			</template>
																		</children>
																	</tgridcell>
																	<tgridcell>
																		<properties valign="top"/>
																		<children>
																			<template subtype="element" match="содержание">
																				<children>
																					<content subtype="regular">
																						<styles white-space="pre-line"/>
																					</content>
																				</children>
																				<variables/>
																			</template>
																		</children>
																	</tgridcell>
																</children>
															</tgridrow>
														</children>
														<variables/>
													</template>
												</children>
												<variables/>
											</template>
										</children>
									</tgridbody-rows>
								</children>
								<wizard-data-repeat>
									<children/>
								</wizard-data-repeat>
								<wizard-data-rows>
									<children/>
								</wizard-data-rows>
								<wizard-data-columns>
									<children/>
								</wizard-data-columns>
							</tgrid>
							<newline/>
							<newline/>
							<newline/>
							<newline/>
							<newline/>
							<newline/>
							<newline/>
							<tgrid tablegen-filter-periods-to-month="12" tablegen-filter-periods-to-day="31">
								<properties border="0" width="100%"/>
								<children>
									<tgridbody-cols>
										<children>
											<tgridcol>
												<styles width="2.27in"/>
											</tgridcol>
											<tgridcol>
												<styles width="2.70in"/>
											</tgridcol>
											<tgridcol/>
										</children>
									</tgridbody-cols>
									<tgridbody-rows>
										<children>
											<tgridrow>
												<children>
													<tgridcell>
														<styles text-align="left"/>
														<children>
															<template subtype="element" match="приказ_о_мерах_по_об_исп_ф_з">
																<children>
																	<template subtype="element" match="подпись">
																		<children>
																			<template subtype="element" match="должность">
																				<children>
																					<content subtype="regular"/>
																				</children>
																				<variables/>
																			</template>
																		</children>
																		<variables/>
																	</template>
																</children>
																<variables/>
															</template>
														</children>
													</tgridcell>
													<tgridcell>
														<styles text-align="center"/>
														<children>
															<template subtype="element" match="приказ_о_мерах_по_об_исп_ф_з">
																<children>
																	<template subtype="element" match="подпись">
																		<children>
																			<template subtype="element" match="подпись">
																				<children>
																					<content subtype="regular"/>
																				</children>
																				<variables/>
																			</template>
																		</children>
																		<variables/>
																	</template>
																</children>
																<variables/>
															</template>
														</children>
													</tgridcell>
													<tgridcell>
														<styles text-align="right"/>
														<children>
															<template subtype="element" match="приказ_о_мерах_по_об_исп_ф_з">
																<children>
																	<template subtype="element" match="подпись">
																		<children>
																			<template subtype="element" match="фио">
																				<children>
																					<content subtype="regular"/>
																				</children>
																				<variables/>
																			</template>
																		</children>
																		<variables/>
																	</template>
																</children>
																<variables/>
															</template>
														</children>
													</tgridcell>
												</children>
											</tgridrow>
										</children>
									</tgridbody-rows>
								</children>
								<wizard-data-repeat>
									<children/>
								</wizard-data-repeat>
								<wizard-data-rows>
									<children/>
								</wizard-data-rows>
								<wizard-data-columns>
									<children/>
								</wizard-data-columns>
							</tgrid>
							<newline/>
						</children>
						<variables/>
					</template>
				</children>
			</globaltemplate>
		</children>
	</mainparts>
	<globalparts/>
	<designfragments/>
	<xmltables/>
	<authentic-custom-toolbar-buttons/>
</structure>
