<div tabindex="0" class="thread-body" role="presentation">
    <div class="body undoreset" tabindex="0">
        <div class="email-wrapped">
            <div>
                <div>
                    <style type="text/css">
                        #yiv8835080492 html {} #yiv8835080492 body {
                            width: 100%;
                            margin: 0 auto;
                            padding: 0;
                        }
						img.displayed {
							display: block;
							margin-left: auto;
							margin-right: auto }
                    </style>
                    <table style="table-layout:fixed;" dir="ltr" width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="min-width:500px;" valign="top" align="center">

                                    <table width="500" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td>
													<img  src="http://cop.viscontiproject.eu/web/images/avatar_big.png" class="displayed">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    <table width="500" bgcolor="#f4f3f8" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="yiv8835080492m1930" style="font-family:Helvetica, Verdana, Arial, sans-serif;font-size:19px;line-height:23px;color:#464958;padding-left:30px;padding-right:30px;">
                                                        Hello ,
														<br>
														<table cellpadding="0" cellspacing="0">
															<tbody>
																<tr>
																	<td style="line-height:24px;" height="24"></td>
																</tr>
															</tbody>
														</table>
														You have recently let us know that you need to reset your password. Please follow this link: 
														
														<?= 

                                                        '<a href="'.Yii::$app->urlManager->createAbsoluteUrl(
                                                            ['site/recovery','mail'=>$mail,'key'=>$key,'type'=>$type]
                                                        ). '">'.Yii::$app->urlManager->createAbsoluteUrl(
                                                            ['site/recovery','mail'=>$mail,'key'=>$key,'type'=>$type]
                                                        ).'</a>' 

                                                        ?>
														<br>
														<table cellpadding="0" cellspacing="0">
															<tbody>
																<tr>
																	<td style="line-height:24px;" height="24"></td>
																</tr>
															</tbody>
														</table>
                                                        Best Regards,<br> VISConti Team.
														<br>
														<table cellpadding="0" cellspacing="0">
															<tbody>
																<tr>
																	<td style="line-height:24px;" height="24"></td>
																</tr>
															</tbody>
														</table>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>