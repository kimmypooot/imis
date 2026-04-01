-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 06:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cscro8_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cdl_clients`
--

CREATE TABLE `cdl_clients` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `agency` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `ao` int(11) DEFAULT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` varchar(12) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='4';

--
-- Dumping data for table `cdl_clients`
--

INSERT INTO `cdl_clients` (`id`, `fname`, `lname`, `agency`, `purpose`, `ao`, `remarks`, `status`, `timestamp`) VALUES
(2, 'LORENZ GABRIEL', 'SABALZA', 'EVSU', 'OJT', 1, 'Tesrt', 'Acted', '2025-04-28 02:32:15'),
(10, 'HAROLD IAN', 'SABLAWON', 'EVSU', 'OJT REQUIREMENTS', 1, 'Ok na nakag klaro hiya hin requirements', 'Acted', '2025-04-28 08:48:06'),
(11, 'LORENZ', 'SABALZA', 'BASTA LA', 'AMBOT', 1, 'Waray niya buot', 'Acted', '2025-04-29 00:35:52'),
(12, 'LORENZ', 'GABRIEL', 'EVSU', 'OTHS', 1, 'client was asking for a copy of his CSE results', 'Acted', '2025-04-29 00:41:17'),
(13, 'HAROLD IAN', 'SABLAWON', 'EVSU', 'NOTHING', 1, 'done', 'Acted', '2025-04-29 01:41:39'),
(14, 'JACK', 'BRIMSTONE', 'NBA', 'GATHERING OF INTEL', 1, 'done', 'Acted', '2025-04-29 01:42:45'),
(15, 'JASON JOSEPH', 'HOLANDA', 'BASTA', 'AMBOT', 1, 'Clients request was the CSE exam comex schedules and requirements', 'Acted', '2025-04-29 02:47:26'),
(16, 'LEOBERT', 'BADANGO', 'EVSU', 'OJT', 2, 'ok na', 'Acted', '2025-04-29 04:21:06'),
(17, 'JASON JOSEPH', 'HOLANDA', 'EVSU', 'OJT', 1, 'oks na', 'Acted', '2025-04-29 04:29:30'),
(18, 'LORENZ GABRIEL', 'SABALZA', 'EVSU', 'OJT', 1, 'clients asked for a handbook', 'Acted', '2025-05-08 05:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `cts_ao_updates`
--

CREATE TABLE `cts_ao_updates` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `date_received_by_ao` date DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `case_docket_no` varchar(100) DEFAULT NULL,
  `case_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_ao_updates`
--

INSERT INTO `cts_ao_updates` (`id`, `case_id`, `date_received_by_ao`, `action_taken`, `case_docket_no`, `case_type`, `created_at`) VALUES
(9, 9, '2025-05-08', NULL, '0345123', 'Non Disciplinary', '2025-05-08 01:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `cts_case_addressee`
--

CREATE TABLE `cts_case_addressee` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `addressee` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_case_addressee`
--

INSERT INTO `cts_case_addressee` (`id`, `case_id`, `addressee`, `timestamp`) VALUES
(31, 9, 20, '2025-05-08 03:17:20'),
(32, 9, 17, '2025-05-08 03:17:31');

-- --------------------------------------------------------

--
-- Table structure for table `cts_case_logs`
--

CREATE TABLE `cts_case_logs` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_case_logs`
--

INSERT INTO `cts_case_logs` (`id`, `case_id`, `message`, `status`, `timestamp`) VALUES
(49, 9, 'New Case Received: Simple Misconduct - Lorenz Gabriel Sabalza', 'Received', '2025-05-08 09:16:35'),
(50, 9, 'Case Update: Case is being assigned to 437-C (Lorenz Gabriel Sabalza)', 'Assigning', '2025-05-08 09:31:49'),
(51, 9, 'Case Update: 437-C has received the document and has been assigned to this case', 'Pending', '2025-05-08 10:58:30'),
(52, 9, 'Drafted', 'Pending', '2025-05-08 11:04:17'),
(53, 9, 'Case Update: Case has been moved to Post-Decision Actions.', 'Post-Pending', '2025-05-08 11:04:38'),
(54, 9, 'Case Update: Case has been resolved', 'Resolved', '2025-05-08 11:05:00'),
(55, 9, 'Case Update: Case status has been update to \"For Releasing\"', 'For Releasing', '2025-05-08 11:09:11');

-- --------------------------------------------------------

--
-- Table structure for table `cts_decision_resolution`
--

CREATE TABLE `cts_decision_resolution` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_decision_resolution`
--

INSERT INTO `cts_decision_resolution` (`id`, `category_name`) VALUES
(1, 'Administrative Charges/Dismissed'),
(2, 'Administrative Investigation/Dismissed'),
(3, 'Answer'),
(4, 'Appeal/Dismissed'),
(5, 'Appeal/Granted'),
(6, 'Appeal/Moot & Academic'),
(7, 'Appeal/Partly Granted'),
(8, 'Appeal/Withdrawn'),
(9, 'Case/Dismissed'),
(10, 'Case/Referred to Agency'),
(11, 'Case/Remanded to Agency'),
(12, 'Case/Remanded to CSCROs'),
(13, 'Complaint/Dismissed'),
(14, 'Directed to explain in writing why should not be cited for indirect contempt'),
(15, 'Exonerated'),
(16, 'Formal Charge'),
(17, 'Guilty'),
(18, 'Indirect Contempt Charge/Dismissed'),
(19, 'Manifestation/Noted'),
(20, 'Motion/Denied'),
(21, 'Motion/Granted'),
(22, 'Motion for Clarification/Denied'),
(23, 'Motion for Clarification/Granted'),
(24, 'Motion for Clarification/Partially Granted'),
(25, 'Motion for Conversion of Penalty of Suspension to Fine/Granted'),
(26, 'Motion for Conversion of Penalty of Suspension to Fine/Denied'),
(27, 'Motion for Deferment/Denied'),
(28, 'Motion for Deferment/Granted'),
(29, 'Motion for Execution or Implementation/Denied'),
(30, 'Motion for Execution or Implementation/Granted'),
(31, 'Motion for Execution or Implementation/Moot & Academic'),
(32, 'Motion for Execution or Implementation/Partly Granted'),
(33, 'Motion for Exoneration/Denied'),
(34, 'Motion for Exoneration/Granted'),
(35, 'Motion for Partial Reconsideration/Denied'),
(36, 'Motion for Partial Reconsideration/Granted'),
(37, 'Motion for Preventive Suspension/Denied'),
(38, 'Motion for Preventive Suspension/Granted'),
(39, 'Motion for Preventive Suspension/Moot & Academic'),
(40, 'Motion for Reconsideration/Denied'),
(41, 'Motion for Reconsideration/Granted'),
(42, 'Motion for Reconsideration/Partially Granted'),
(43, 'Motion for Suspension of Proceedings/Denied'),
(44, 'Motion for Suspension of Proceedings/Granted'),
(45, 'Motion for Withdrawal/Denied'),
(46, 'Motion for Withdrawal/Granted'),
(47, 'Motion to Dismiss/Denied'),
(48, 'Motion to Dismiss/Granted'),
(49, 'Motion to Inhibit/Denied'),
(50, 'Motion to Inhibit/Granted'),
(51, 'Petition/Granted'),
(52, 'Petition/Dismissed'),
(53, 'Petition or Motion for Indirect Contempt/Denied'),
(54, 'Petition or Motion for Indirect Contempt/Granted'),
(55, 'Petition for Review/Dismissed'),
(56, 'Petition for Review/Granted'),
(57, 'Petition for Review/Partially Granted'),
(58, 'Protest/Dismissed'),
(59, 'Protest/Granted'),
(60, 'Report of Investigation'),
(61, 'Request/Denied'),
(62, 'Request/Granted'),
(63, 'Request/Noted'),
(64, 'Request/Partly Granted'),
(65, 'Request for Transfer of Venue/Denied'),
(66, 'Request for Transfer of Venue/Granted'),
(67, 'Second Motion for Reconsideration/Denied'),
(68, 'Second Motion for Reconsideration/Granted'),
(69, 'Replied');

-- --------------------------------------------------------

--
-- Table structure for table `cts_for_releasing`
--

CREATE TABLE `cts_for_releasing` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `addressee` varchar(255) DEFAULT NULL,
  `office_address` text DEFAULT NULL,
  `rrr_no` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_received` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_for_releasing`
--

INSERT INTO `cts_for_releasing` (`id`, `case_id`, `addressee`, `office_address`, `rrr_no`, `created_at`, `updated_at`, `date_received`) VALUES
(9, 9, NULL, NULL, NULL, '2025-05-08 01:16:35', '2025-05-08 01:16:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cts_list_of_addressee`
--

CREATE TABLE `cts_list_of_addressee` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `rrr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_list_of_addressee`
--

INSERT INTO `cts_list_of_addressee` (`id`, `name`, `address`, `rrr`) VALUES
(2, 'Lorenz Gabriel Sabalza', 'Brgy. Magay, Tanauan, Leyte', '12345'),
(17, 'Lorenz Gabriel Sabalza', 'Brgy. Magay, Tanauan, Leyte', '123456'),
(18, 'Lorenz Gabriel Sabalza Jr', 'Brgy. Magay, Tanauan, Leyte', '12345123'),
(19, 'Harold', 'Brgy. Magay, Tanauan, Leyte', '1234'),
(20, 'Harold', 'Brgy. Magay, Tanauan, Leyte', '1223345');

-- --------------------------------------------------------

--
-- Table structure for table `cts_manage_case`
--

CREATE TABLE `cts_manage_case` (
  `id` int(11) NOT NULL,
  `action_officer_id` int(11) DEFAULT NULL,
  `date_received_office` date DEFAULT NULL,
  `date_received_lsd` date DEFAULT NULL,
  `signatory_name` varchar(255) DEFAULT NULL,
  `document_date` date DEFAULT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `taxonomy` varchar(255) DEFAULT NULL,
  `party` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_received` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_manage_case`
--

INSERT INTO `cts_manage_case` (`id`, `action_officer_id`, `date_received_office`, `date_received_lsd`, `signatory_name`, `document_date`, `document_type`, `taxonomy`, `party`, `status`, `created_at`, `updated_at`, `is_received`) VALUES
(9, 2, '2025-05-08', '2025-05-08', 'Lorenz Gabriel Sabalza', '2025-05-01', 'Letter', 'Simple Misconduct', 'Lorenz Gabriel Sabalza', 'For Releasing', '2025-05-08 01:16:35', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cts_manage_users`
--

CREATE TABLE `cts_manage_users` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `ao_number` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_manage_users`
--

INSERT INTO `cts_manage_users` (`id`, `user`, `ao_number`) VALUES
(1, 2, '427-S'),
(2, 1, '437-C');

-- --------------------------------------------------------

--
-- Table structure for table `cts_penalty`
--

CREATE TABLE `cts_penalty` (
  `id` int(11) NOT NULL,
  `penalty_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_penalty`
--

INSERT INTO `cts_penalty` (`id`, `penalty_name`) VALUES
(1, 'CS Professional Eligibility/Cancelled'),
(2, 'CS Sub-Professional Eligibility/Cancelled'),
(3, 'Demotion (next lower position or diminution of salary to next lower grade)'),
(4, 'Dismissal from the Service'),
(5, 'Dropped from the Rolls'),
(6, 'Fine equivalent to five (5) months salary'),
(7, 'Fine equivalent to four (4) months salary'),
(8, 'Fine equivalent to one (1) month salary'),
(9, 'Fine equivalent to six (6) months salary'),
(10, 'Fine equivalent to three (3) months salary'),
(11, 'Fine equivalent to two (2) months salary'),
(12, 'Fine of P1,000.00 for every day of non-implementation'),
(13, 'Preventive Suspension'),
(14, 'Preventive Suspension for 30 days'),
(15, 'Preventive Suspension for 60 days'),
(16, 'Preventive Suspension for 90 days'),
(17, 'Reprimand'),
(18, 'Suspension from the Service for one (1) year'),
(19, 'Suspension from the Service for fifteen (15) days'),
(20, 'Suspension from the Service for nine (9) months'),
(21, 'Suspension from the Service for nine (9) months and one (1) day'),
(22, 'Suspension from the Service for one (1) month and one (1) day'),
(23, 'Suspension from the Service for six (6) months'),
(24, 'Suspension from the Service for six (6) months and one (1) day'),
(25, 'Suspension from the Service for thirty (30) days or one (1) month'),
(26, 'Suspension from the Service for three (3) months'),
(27, 'Suspension from the Service for three (3) months and one (1) day'),
(28, 'Suspension from the Service for two (2) months'),
(29, 'Terminated from the Service');

-- --------------------------------------------------------

--
-- Table structure for table `cts_post_decision_actions`
--

CREATE TABLE `cts_post_decision_actions` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `dec_reso_no` varchar(255) DEFAULT NULL,
  `dec_reso_date` date DEFAULT NULL,
  `gist_of_action` int(11) DEFAULT NULL,
  `penalty` int(11) DEFAULT NULL,
  `remarks_to_gist` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_received` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_post_decision_actions`
--

INSERT INTO `cts_post_decision_actions` (`id`, `case_id`, `dec_reso_no`, `dec_reso_date`, `gist_of_action`, `penalty`, `remarks_to_gist`, `created_at`, `is_received`) VALUES
(9, 9, '12345', '2025-05-08', 1, 1, 'Nothing', '2025-05-08 01:16:35', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cts_taxonomy`
--

CREATE TABLE `cts_taxonomy` (
  `id` int(11) NOT NULL,
  `type` varchar(124) NOT NULL,
  `name` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cts_taxonomy`
--

INSERT INTO `cts_taxonomy` (`id`, `type`, `name`) VALUES
(1, 'Disciplinary Cases', 'Abuse of Authority'),
(2, 'Disciplinary Cases', 'Borrowing Money by Superior Officers from Subordinates'),
(3, 'Disciplinary Cases', 'Conduct Prejudicial to the Best Interest of the Service'),
(4, 'Disciplinary Cases', 'Contracting Loans of Money or other Property from Persons with whom the Office of the Employee has Business Relations'),
(5, 'Disciplinary Cases', 'Conviction of a Crime Involving Moral Turpitude'),
(6, 'Disciplinary Cases', 'Directly or Indirectly having Financial and Material Interest in any Transaction requiring the Approval of his/her Office.  Financial and Material Interest is defined as pecuniary or propriety interest by which a person will gain or lose something'),
(7, 'Disciplinary Cases', 'Disclosing or Misusing Confidential or Classified Information Officially known to him/her by reason of his/her Office and not made available to the Public, to futher his/her private interests or give undue advantage to anyone, or to prejudice the public interest;'),
(8, 'Disciplinary Cases', 'Discourtesy in the Course of Official Duties'),
(9, 'Disciplinary Cases', 'Disgraceful and Immoral Conduct'),
(10, 'Disciplinary Cases', 'Disgraceful, Immoral or Dishonest Conduct Prior to Entering the Service'),
(11, 'Disciplinary Cases', 'Disloyalty to the Republic of the Philippines and to the Filipino People'),
(12, 'Disciplinary Cases', 'Engaging Directly or Indirectly in Partisan Political Activities by One Holding Non-Political Office'),
(13, 'Disciplinary Cases', 'Engaging in Private Practice of his/her Profession unless authorized by the Constitution, law or regulation, provided that such practice will not conflict with his/her official functions'),
(14, 'Disciplinary Cases', 'Failure to Act Promptly on Letters and Request within Fifteen (15) working days from receipt, except otherwise provided in the rules implementing the Code of Conduct and Ethical Standards for Public Officials and Employees'),
(15, 'Disciplinary Cases', 'Failure to Attend to Anyone who wants to Avail Himself/Herself of the Services of the Office, or Act Promptly and Expeditiously on Public Transactions'),
(16, 'Disciplinary Cases', 'Failure to File Sworn Statements of Assets, Liabilities and Net Worth, and Disclosure of Business Interest and Financial Connection including those of Their spouses and unmarried children under eighteen (18) years of age living in their Households'),
(17, 'Disciplinary Cases', 'Failure to Process Documents and Complete Action on Documents and Papers within a Reasonable Time from Preparation thereof, except otherwise provided in the rules implementing the Code of Conduct and Ethical Standards for Public Officials and Employees'),
(18, 'Disciplinary Cases', 'Failure to Resign from his/her position in the Private Business Enterprise within Thirday (30) days from Assumption of Public Office when conflict of interest arises, and/or failure to divest himself/herselft of his/her shareholdings or interest in private business enterprise within sixty (60) days from assumption of public office when conflict of interest arises; Provided, however, that for those who are already in the service and conflict of interest arises, the official or employee must either resign or divest himself/herself of said interest within the periods hereinabove provided, reckoned from the date when the conflict of interest had arisen'),
(19, 'Disciplinary Cases', 'Falsification of Official Document/s'),
(20, 'Disciplinary Cases', 'Frequest Unauthorized Absences'),
(21, 'Disciplinary Cases', 'Frequest Unauthorized Tardiness in Reporting for Duty (Habitual Tardiness)'),
(22, 'Disciplinary Cases', 'Loafing from Duty during Regular Office Hours'),
(23, 'Disciplinary Cases', 'Gambling Prohibited by Law'),
(24, 'Disciplinary Cases', 'Grave Misconduct'),
(25, 'Disciplinary Cases', 'Gross Insubordination'),
(26, 'Disciplinary Cases', 'Gross Neglect of Duty'),
(27, 'Disciplinary Cases', 'Habitual Drunkenness'),
(28, 'Disciplinary Cases', 'Improper or Unauthorized Solicitation of Contributions from Subordinate Employees and by Teachers or School Officials from School Children'),
(29, 'Disciplinary Cases', 'Indirect Contempt'),
(30, 'Disciplinary Cases', 'Inefficiency and Incompetence in the Performance of Official Duties'),
(31, 'Disciplinary Cases', 'Insubordination'),
(32, 'Disciplinary Cases', 'Less Serious Dishonesty'),
(33, 'Disciplinary Cases', 'Lobbying for Personal Interest or Gain in Legislative Halls and Offices without Authority'),
(34, 'Disciplinary Cases', 'Nepotism'),
(35, 'Disciplinary Cases', 'Obtaining or Using any Statement filed under the Code of Conduct and Ethical Standards for Public Officials and Employees for any Purpose Contrary to Morals or Public Policy or commercial purpose other than by news and communications media for dissemination to the general public'),
(36, 'Disciplinary Cases', 'Oppression'),
(37, 'Disciplinary Cases', 'Owning, Controlling, Managing or Accepting Employment as Officer, Employee, Consultant, Counsel, Broker, Agent, Trustee, or Nominee in any Private Enterprise regulated, supervised or licensed by his/her Office, unless expressly allowed by law'),
(38, 'Disciplinary Cases', 'Physical or Mental Incapacity or Disability due to immoral or vicious habits'),
(39, 'Disciplinary Cases', 'Promoting the Sale of Tickets in Behalf of Private Enterprises that are not intended for charitable or public welfare purposes and even in the latter cases, if there is no prior authority'),
(40, 'Disciplinary Cases', 'Pursuit of Private Business, Vocation or Profession without the Permission by Civil Service Rules and Regulations'),
(41, 'Disciplinary Cases', 'Receiving for Personal Use of a Fee, Gift or other Valuable thing in the Course of Official Duties or in connection therewith when such fee, gift or other valuable thing is given by any person in the hope or expectation of receiving a favor or better treatment than that accorded to other persons, or committing acts punishable under the anti-graft laws'),
(42, 'Disciplinary Cases', 'Recommending any person to any position in a private enterprise which has a regular or pending official transaction with his/her office, unless such recommendation or referral is mandated by (1) law, or (2) international agreements, commitment and obligation, or as part of the function of his/her office'),
(43, 'Disciplinary Cases', 'Refusal to Perform Official Duty'),
(44, 'Disciplinary Cases', 'Refusal to Render Overtime Service'),
(45, 'Disciplinary Cases', 'Serious Dishonesty'),
(46, 'Disciplinary Cases', 'Sexual Harassment'),
(47, 'Disciplinary Cases', 'Simple Discourtesy in the Couse of Official Duties'),
(48, 'Disciplinary Cases', 'Simple Misconduct'),
(49, 'Disciplinary Cases', 'Simple Neglect of Duty'),
(50, 'Disciplinary Cases', 'Soliciting or Accepting Directly or Indirectly, any Gift, Gratuity, Favor, Entertainment, Loan or Anything of Monetary Value which in the Course of his/her Official Duties or in connection with any operation being regulated by, or any transaction which may be affected by the functions of his/her office.  The propriety or impropriety of the foregoing shall be determined by its value, kinship, or relationship between giver and receiver and the motivation.  A thing of monetary value is one which is evidently or manifestly excessive by its very nature'),
(51, 'Disciplinary Cases', 'Transfer of Venue'),
(52, 'Disciplinary Cases', 'Unfair Discrimination in Rendering Public Service due to Party Affiliation or Preference'),
(53, 'Disciplinary Cases', 'Violation of Existing Civil Service Law and Rules of Serious Nature'),
(54, 'Disciplinary Cases', 'Violation of Reasonable Office Rules and Regulations'),
(55, 'Disciplinary Cases', 'Violation of Republic Act No. 3019'),
(56, 'Disciplinary Cases', 'Violation of Republic Act No. 6713'),
(57, 'Disciplinary Cases', 'Violation of Republic Act No. 9485'),
(58, 'Disciplinary Cases', 'Willful Failure to Pay Just Debts'),
(59, 'Disciplinary Cases', 'Willful Failure to Pay Taxes due to the Government'),
(60, 'Non-Disciplinary Cases', 'Accreditation of Service'),
(61, 'Non-Disciplinary Cases', 'Backwages/Back Salaries'),
(62, 'Non-Disciplinary Cases', 'Claims for Benefits (Leave & Other Benefits)'),
(63, 'Non-Disciplinary Cases', 'Correction of Personal Information'),
(64, 'Non-Disciplinary Cases', 'Demotion'),
(65, 'Non-Disciplinary Cases', 'Detail'),
(66, 'Non-Disciplinary Cases', 'Disapproval of Appointment/s'),
(67, 'Non-Disciplinary Cases', 'Dropping from the Rolls; Absence Without Approved Leave (AWOL)'),
(68, 'Non-Disciplinary Cases', 'Dropping from the Rolls; Physically Unfit'),
(69, 'Non-Disciplinary Cases', 'Dropping from the Rolls; Unsatisfactory or Poor Performance'),
(70, 'Non-Disciplinary Cases', 'Extension of Service'),
(71, 'Non-Disciplinary Cases', 'Invalidation of Appointment/s'),
(72, 'Non-Disciplinary Cases', 'Promotion'),
(73, 'Non-Disciplinary Cases', 'Protest'),
(74, 'Non-Disciplinary Cases', 'Reassignment'),
(75, 'Non-Disciplinary Cases', 'Recall of the Approval of Appointment'),
(76, 'Non-Disciplinary Cases', 'Re-employment'),
(77, 'Non-Disciplinary Cases', 'Reinstatement'),
(78, 'Non-Disciplinary Cases', 'Removal of Administrative Penalties/Disabilities'),
(79, 'Non-Disciplinary Cases', 'Reorganization'),
(80, 'Non-Disciplinary Cases', 'Conversion of Position/s from Non-Career to Career'),
(81, 'Non-Disciplinary Cases', 'Conversion of Position/s from Career to Non-Career'),
(82, 'Non-Disciplinary Cases', 'Revocation of Appointment/s'),
(83, 'Non-Disciplinary Cases', 'Secondment'),
(84, 'Non-Disciplinary Cases', 'Special Leave for Women'),
(85, 'Non-Disciplinary Cases', 'Termination/Separation from the Service'),
(86, 'Non-Disciplinary Cases', 'Transfer'),
(91, '', 'Reversion to Use of Maiden Name in the Personnel Records'),
(92, '', 'Basta'),
(93, 'Disciplinary Cases', 'Waray pa'),
(94, 'Non-Disciplinary Cases', 'Nangawat'),
(95, 'Non-Disciplinary Cases', 'Nanlabay hin balay');

-- --------------------------------------------------------

--
-- Table structure for table `system_access`
--

CREATE TABLE `system_access` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user` int(11) NOT NULL,
  `otrs` varchar(10) NOT NULL,
  `eris` varchar(10) NOT NULL,
  `ors` varchar(10) NOT NULL,
  `cdl` varchar(10) NOT NULL,
  `iis` varchar(10) NOT NULL,
  `rfcs` varchar(10) NOT NULL,
  `dvs` varchar(10) NOT NULL,
  `cts` varchar(10) NOT NULL,
  `lms` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `system_access`
--

INSERT INTO `system_access` (`id`, `timestamp`, `user`, `otrs`, `eris`, `ors`, `cdl`, `iis`, `rfcs`, `dvs`, `cts`, `lms`) VALUES
(1, '2025-04-24 00:52:20', 2, 'User', 'None', 'Admin', 'Admin', 'Admin', 'None', 'None', 'Superadmin', 'None'),
(2, '2025-04-24 00:52:57', 1, 'Admin', 'Admin', 'Admin', 'Admin', 'Admin', 'None', 'None', 'User', 'None'),
(3, '2025-04-24 05:01:14', 3, 'None', 'None', 'None', 'Admin', 'Admin', 'None', 'None', 'None', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `users_cscro8`
--

CREATE TABLE `users_cscro8` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `minitial` varchar(2) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `fo_rsu` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `status` varchar(12) NOT NULL,
  `type` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users_cscro8`
--

INSERT INTO `users_cscro8` (`id`, `fname`, `lname`, `mname`, `minitial`, `username`, `password`, `email`, `position`, `fo_rsu`, `birthday`, `status`, `type`, `role`, `profile`, `timestamp`) VALUES
(1, 'Lorenz Gabriel', 'Sabalza', 'Hamili', 'H', 'superadmin', '$2y$10$RhXcMiZi4WsTRCPBhZu2M.SZmErbz577wDvWW9yDEnNFrMI.IIblm', 'lgsabalza@gmail.com', 'Systems Developer', 'Legal Services Division', '2002-07-14', 'Active', 'lsd', 'admin', 'user_1_6809bd95e29ef.jpg', '2025-04-10 01:43:21'),
(2, 'Harold Ian KK', 'Sablawon', 'Catalia', 'C', 'haroldIan', '$2y$10$C/gtUBBtxCbY/3Y4dxXTneQZudID77v8O7xP9VHS.s8FG0ugnEm2W', 'haroldIan@gmail.com', 'Developer', 'Legal Services Division', '2002-01-02', 'Active', 'lsd', 'admin', 'user_2_6809bd899eca5.jpg', '2025-04-24 00:52:20'),
(3, 'Jason Joseph', 'Holanda', 'Primo', 'P', 'jason', '$2y$10$hkeKHPlWyl7QboA7UbFOueWdx3BPNqfI28H5IBbf2VpK4PaCwOgT2', 'jason@gmail.com', 'Developer', 'Human Resource Division', '2002-01-01', 'Active', 'hrd', 'admin', '6809c59a87952.jpg', '2025-04-24 05:01:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cdl_clients`
--
ALTER TABLE `cdl_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cts_ao_updates`
--
ALTER TABLE `cts_ao_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cts_ao_updates_ibfk_1` (`case_id`);

--
-- Indexes for table `cts_case_addressee`
--
ALTER TABLE `cts_case_addressee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cts_case_logs`
--
ALTER TABLE `cts_case_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cts_ao_assign_log_ibfk_1` (`case_id`);

--
-- Indexes for table `cts_decision_resolution`
--
ALTER TABLE `cts_decision_resolution`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cts_for_releasing`
--
ALTER TABLE `cts_for_releasing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_case_id` (`case_id`);

--
-- Indexes for table `cts_list_of_addressee`
--
ALTER TABLE `cts_list_of_addressee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cts_manage_case`
--
ALTER TABLE `cts_manage_case`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_action_officer` (`action_officer_id`);

--
-- Indexes for table `cts_manage_users`
--
ALTER TABLE `cts_manage_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ao_number` (`ao_number`);

--
-- Indexes for table `cts_penalty`
--
ALTER TABLE `cts_penalty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cts_post_decision_actions`
--
ALTER TABLE `cts_post_decision_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cts_post_decision_actions_ibfk_1` (`case_id`);

--
-- Indexes for table `cts_taxonomy`
--
ALTER TABLE `cts_taxonomy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_access`
--
ALTER TABLE `system_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user`);

--
-- Indexes for table `users_cscro8`
--
ALTER TABLE `users_cscro8`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cdl_clients`
--
ALTER TABLE `cdl_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `cts_ao_updates`
--
ALTER TABLE `cts_ao_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cts_case_addressee`
--
ALTER TABLE `cts_case_addressee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `cts_case_logs`
--
ALTER TABLE `cts_case_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `cts_decision_resolution`
--
ALTER TABLE `cts_decision_resolution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `cts_for_releasing`
--
ALTER TABLE `cts_for_releasing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cts_list_of_addressee`
--
ALTER TABLE `cts_list_of_addressee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `cts_manage_case`
--
ALTER TABLE `cts_manage_case`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cts_manage_users`
--
ALTER TABLE `cts_manage_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cts_penalty`
--
ALTER TABLE `cts_penalty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `cts_post_decision_actions`
--
ALTER TABLE `cts_post_decision_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cts_taxonomy`
--
ALTER TABLE `cts_taxonomy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `system_access`
--
ALTER TABLE `system_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_cscro8`
--
ALTER TABLE `users_cscro8`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cts_ao_updates`
--
ALTER TABLE `cts_ao_updates`
  ADD CONSTRAINT `cts_ao_updates_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cts_manage_case` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cts_case_logs`
--
ALTER TABLE `cts_case_logs`
  ADD CONSTRAINT `cts_case_logs_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cts_manage_case` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cts_for_releasing`
--
ALTER TABLE `cts_for_releasing`
  ADD CONSTRAINT `fk_case_id` FOREIGN KEY (`case_id`) REFERENCES `cts_manage_case` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cts_manage_case`
--
ALTER TABLE `cts_manage_case`
  ADD CONSTRAINT `fk_action_officer` FOREIGN KEY (`action_officer_id`) REFERENCES `cts_manage_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `cts_post_decision_actions`
--
ALTER TABLE `cts_post_decision_actions`
  ADD CONSTRAINT `cts_post_decision_actions_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cts_manage_case` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `system_access`
--
ALTER TABLE `system_access`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user`) REFERENCES `users_cscro8` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
