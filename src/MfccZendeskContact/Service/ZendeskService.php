<?php
/**
 * Copyright (c) 2011-2013 Jan Tlapak <jan@mfcc.cz>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category  ZendeskContact
 * @package   Mfcc
 * @author    Jan Tlapak <jan@mfcc.cz>
 * @copyright 2011-2013 Jan Tlapak
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 */
namespace MfccZendeskContact\Service;
 
/**
 * Zendesk Service
 */
class ZendeskService {
	
	/**
     * Api key for zendesk
     *
     * @var string $zdApiKey
     */
	protected $zdApiKey; 
	
	/**
     * Zendesk user
     *
     * @var string $zdUser
     */
	protected $zdUser; 
	
	/**
     * Zendesk url
     *
     * @var string $zdUrl
     */
	protected $zdUrl; 
	
	/**
	 * Ticket locale
	 * 
	 * @var integer $locale
	 */
	protected $locale;
	
	/**
	 * Ticket priority
	 * 
	 * @var string $priority
	 */
	protected $priority;
	
	/**
	 * Ticket type
	 * 
	 * @var string $type
	 */
	protected $type;
	
	/**
	 * Ticket status
	 * 
	 * @var string $status
	 */
	protected $status;
	
	/**
	 * Ticket group ID
	 * 
	 * @var integer $group_id
	 */
	protected $group_id;
	
	
	/**
	 * Ticket assignee ID
	 * 
	 * @var integer $assignee_id
	 */
	protected $assignee_id;
	
	/**
	 * Ticket subject
	 * 
	 * @var string $subject
	 */
	protected $subject;
	
	/**
	 * Ticket collaborators
	 * 
	 * @var array of integers $collaborators
	 */
	protected $collaborators = array();
	
	/**
	 * Ticket tags
	 * 
	 * @var array of strings $tags
	 */
	protected $tags = array();
	
	/**
	 * Ticket custom fields
	 * 
	 * @var associative array $custom_fields
	 */
	protected $custom_fields = array();
	
	
	/**
	 * Creates new ticket in zendesk
	 * 
	 * @param string $name 
	 * @param string $email 
	 * @param string $body 
	 */
	public function createTicket($name, $email, $body)
	{
		$data['ticket'] = array(
				'subject' => $this->subject,
				'comment' => array('body' => $body),
				'requester' => array(
						'locale_id' => $this->locale,
						'name' => $name,
						'email' => $email
				),
				'priority' => $this->priority,
				'type' => $this->type,
				'status' => $this->status, 
				'group_id' => $this->group_id, // support group id
				'assignee_id' => $this->assignee_id,
				'collaborator_ids' => $this->collaborators,
				'tags' => $this->tags,
				'custom_fields' => $this->custom_fields
		);
		
		$json = json_encode($data);
		
		$data = $this->curlWrap("/tickets.json", $json, "POST");
		
		if ($data) {
			return true;
		} else {
			return false;
		}		
	}
	
	/**
	 * curl wrap
	 *
	 * @param unknown $url
	 * @param unknown $json
	 * @param unknown $action
	 * @return mixed
	 */
	private function curlWrap($url, $json, $action)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_URL, $this->zdUrl.$url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->zdUser."/token:".$this->zdApiKey);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		switch($action){
			case "POST":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
				break;
			case "GET":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				break;
			case "PUT":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
				break;
			case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
			default:
				break;
		}
		 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);
		$decoded = json_decode($output);
		return $decoded;
	}
	
	// Ticket methods
	
	/**
	 * Add ticket collaborator
	 * @param integer $collaboratorId
	 * @return ZendeskService
	 */
	public function addCollaborator($collaboratorId)
	{
		$this->collaborators[] = $collaboratorId;
		return $this;
	}
	
	/**
	 * Clear ticket collaborators
	 * @return ZendeskService
	 */
	public function clearCollaborators()
	{
		$this->collaborators = array();
		return $this;
	}
	
	/**
	 * Add ticket tag
	 * @param string $tag
	 * @return ZendeskService
	 */
	public function addTag($tag)
	{
		$this->tags[] = $tag;
		return $this;
	}
	
	/**
	 * Clear ticket tags
	 * @return ZendeskService
	 */
	public function clearTags()
	{
		$this->tags = array();
		return $this;
	}
	
	/**
	 * Add ticket custom field
	 * @param string $key
	 * @param string $value
	 * @return ZendeskService
	 */
	public function addCustomField($key, $value)
	{
		$this->custom_fields[$key] = $value;
		return $this;
	}
	
	/**
	 * Add ticket custom fields
	 * @param array $customFields
	 * @return ZendeskService
	 */
	public function addCustomFields($customFields)
	{
		$this->custom_fields = array_merge($this->custom_fields, $customFields);
		return $this;
	}
	
	/**
	 * Clear custom fields
	 * @return ZendeskService
	 */
	public function clearCustomFields()
	{
		$this->custom_fields = array();
		return $this;
	}
	
	// Ticket Setters
	
	/**
	 * Set zendesk ticket locale
	 * @param integer $subject
	 * @return ZendeskService
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
		return $this;
	}
	
	/**
	 * Set ticket priority
	 * @param string $priority
	 * @return ZendeskService
	 */
	public function setPriority($priority)
	{
		$this->priority = $priority;
		return $this;
	}
	
	/**
	 * Set ticket group ID
	 * @param integer $groupId
	 * @return ZendeskService
	 */
	public function setGroupId($groupId)
	{
		$this->group_id = $groupId;
		return $this;
	}
	
	/**
	 * Set ticket assignee ID
	 * @param integer $assigneeId
	 * @return ZendeskService
	 */
	public function setAsigneeId($assigneeId)
	{
		$this->assignee_id = $assigneeId;
		return $this;
	}
	
	/**
	 * Set ticket collaborators IDs
	 * @param array $collaborators
	 * @return ZendeskService
	 */
	public function setCollaborators($collaborators)
	{
		$this->collaborators = $collaborators;
		return $this;
	}
	
	/**
	 * Set ticket tags
	 * @param array $tags
	 * @return ZendeskService
	 */
	public function setTags($tags)
	{
		$this->tags = $tags;
		return $this;
	}
	
	/**
	 * Set ticket custom fields
	 * @param array $custom_fields
	 * @return ZendeskService
	 */
	public function setCustomFields($custom_fields)
	{
		$this->custom_fields = $custom_fields;
		return $this;
	}
	
	/**
	 * Set ticket status
	 * @param string $priority
	 * @return ZendeskService
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}
	
	/**
	 * Set ticket type
	 * @param string $type
	 * @return ZendeskService
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}
	
	/**
	 * Set zendesk ticket subject
	 * @param string $subject
	 * @return ZendeskService
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
		return $this;
	}
	
	// Zendesk Setters
	
	/**
	 * Set zendesk Api Key
	 * @param string $zdApiKey
	 * @return ZendeskService
	 */
	public function setZdApiKey($zdApiKey)
	{
		$this->zdApiKey = $zdApiKey;
		return $this;
	}
	
	/**
	 * Set zendesk user
	 * @param string $zdUser
	 * @return ZendeskService
	 */
	public function setZdUser($zdUser)
	{
		$this->zdUser = $zdUser;
		return $this;
	}
	
	/**
	 * Set zendesk url
	 * @param string $zdUrl
	 * @return ZendeskService
	 */
	public function setZdUrl($zdUrl)
	{
		$this->zdUrl = $zdUrl;
		return $this;
	}
	
}