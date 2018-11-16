<?php
	/**
	 * Class Sticky
	 * This class is a Data Object used to represent the state and behavour of a Sticky Note.
	 * State of these objects are stored in private fields, included are associated accesors and mutators.
	 * This class has provided default values for its constructor.
	 * Functions access modifiers are explicitly stated.
	 * @author Christopher Dufort
	 * @version 1.0.0 Release
	 * @since 5.5.X PHP
	 */
	class Sticky{

		//PHP is a loosely typed language, no types are declared for the following private fields.

		//Unique ID associated with each stickynote upon creation
		private $id;
		//Text content of sticky note, entered by user.
		private $text;
		//Left style position of sticky note container.
		private $leftpos;
		//Top style position of sticky note container.
		private $toppos;
		//Z Index style position of sticky nore container.
		private $zindex;

		/**
		 * This is the constructor for the sticky class, this method is called implicity via the new operator.
		 * A constructor can also be called explicitly, and has no access modifier in PHP (Magic Function).
		 * This constructor will call the mutator(setter) methods in order to assign values to fields.
		 * All accepted paramaters have a default value (if an id of -1 is not overwritten an error has occured). 
		 * This constructor is often called when retrieving/storing values in a database of sticky notes.
		 * @param integer $id
		 *					Unique ID of created stickynote, associated with Primary Key of a table.
		 * @param string $text
		 *					Text content of a sticky note, entered by a user.
		 * @param float $leftpos
		 *					Left position of a sticky note.
		 * @param float $toppos
		 *					Top position of a sticky note.
		 * @param integer $zindex
		 *					ZIndex position of a sticky note, representing visible height on screen.
		 */
		function __construct($id=-1, $text="", $leftpos=0.0, $toppos=0.0, $zindex=0){
			$this->setId($id);
			$this->setText($text);
			$this->setLeftpos($leftpos);
			$this->setToppos($toppos);
			$this->setZindex($zindex);
		}

		/**
		 * @return the unique id of the sticky note.
		 */
		public function getId(){
			return $this->id;
		}

		/**
         * @param integer $id
         *				The provided id to be set in the sticky object.         
         */
		public function setId($id){
			$this->id = $id;
		}

		/**
		 * @return the text content of the sticky note.
		 */
		public function getText(){
			return $this->text;
		}

		/**
         * @param string $text
         *				The provided text content to be set in the sticky object.         
         */
		public function setText($text){
			$this->text = $text;
		}

		/**
		 * @return the left position of the sticky note.
		 */
		public function getLeftpos(){
			return $this->leftpos;
		}

		/**
         * @param float $leftpos
         *				The provided left position to be set in the sticky object.  
         */
		public function setLeftpos($leftpos){
			$this->leftpos = $leftpos;
		}

		/**
		 * @return the top position of the sticky note.
		 */
		public function getToppos(){
			return $this->toppos;
		}

		/**
         * @param float $toppos
         *				The provided top position to be set in the sticky object.        
         */
		public function setToppos($toppos){
			$this->toppos = $toppos;
		}

		/**
		 * @return the zindex  height of the sticky note.
		 */
		public function getZindex(){
			return $this->zindex;
		}

		/**
         * @param integer $zindex
         *				The provided zindex position to be set in the sticky object.     
         */
		public function setZindex($zindex){
			$this->zindex = $zindex;
		}

		/**
		 * This function is a magical function used to display a string representation of the object.
		 * This function is called implicitly when echoing an object of class Sticky.  
		 * @return a custome string representation of sticky note detailing value of all fields.
		 */
		public function __toString(){
        	return "id: $this->id <br/>
					text: $this->text <br/>
					leftpos: $this->leftpos <br/>
					toppos: $this->toppos <br/>
					zindex: $this->zindex";
        }
	}