{* 2012 - Autumn Prestashop Theme - Sercan YEMEN - www.withinpixels.com *}

		{if !$content_only}
                        </div> <!-- /column -->
                        
                        {if $HOOK_LEFT_COLUMN}
                            <div id="left-column" class="column one-third"> {$HOOK_LEFT_COLUMN} </div>
                        {/if}
                    
                    </div> <!-- content container -->
                </div> <!-- content-fluid -->
                    
                    <div id="footer-fluid" class="fluid-bg">
                         
                        <div id="footer-top" class="fluid-bg">
                            <div class="container">
                                
                                {if isset($socialLinks) && $socialLinks || isset($customSocialLinks) && $customSocialLinks}
                                    
                                    <div id="footer-top-content" class="column full">
                                            <p class="social-link-info">{l s='Connect with us:'} </p>
                                            
                                            {if isset($socialLinks) && $socialLinks}
                                                {foreach $socialLinks as $socialLink}
                                                    <a class="social-link" title="{$socialLink@key|capitalize}" href="{$socialLink}"><div class="social {$socialLink@key}"></div></a>
                                                {/foreach}
                                            {/if}
                                            
                                            {if isset($customSocialLinks) && $customSocialLinks}
                                                {foreach $customSocialLinks as $customSocialLink}
                                                    <a class="custom-social-link" title="{$customSocialLink.name|capitalize}" href="{$customSocialLink.link}"><img class="custom-social-link-img" src="{$img_dir}/autumn/custom_social_icons/{$customSocialLink.name}.png" /></a>
                                                {/foreach}
                                            {/if}
                                            
                                    </div>
                                {/if}
                                
                            </div>
                        </div>
                        
                        <div id="footer-center" class="fluid-bg">
                            <div class="container">                           
                                <div id="footer" class="column full">
                                        {$HOOK_FOOTER}
                                </div>
                            </div>
                        </div>
                                
                        <div id="footer-bottom" class="fluid-bg">
                            <div class="container">
                                <div id="footer-bottom-content" class="column full">
                                    {l s='AUTUMN STORE © %d.' sprintf=$smarty.now|date_format:'%Y'} {l s='Powered by Prestashop™. All Rights Reserved.'}
                                </div>
                            </div>
                        </div>
                    </div>    
				
                </div> <!-- wrapper -->
	{/if}
	</body>
</html>
