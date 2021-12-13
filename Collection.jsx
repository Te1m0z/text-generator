import React from 'react'

// services
import { storageSes, storageLoc } from '@services/storage.js'

// components
import { EVersion } from '../Eversion/EVersion.jsx'
import { DocSettings } from '../DocSettings/DocSettings.jsx'
import { DocModal } from '@components/Modal'

// UI
import SubmitBtn from '@ui/SubmitBtn'
import FormInput from '@ui/FormInput'
import FormTextarea from '@ui/FormTextarea'

// hooks
import { useTranslation } from 'react-i18next'

export const Collection = ({ doc }) => {

	const [t] = useTranslation('home_page')

	const form = React.useRef();

	const doiNumber = React.useRef();
	const author = React.useRef();
	const issueName = React.useRef();
	const nameCollection = React.useRef();
	const tomeNum = React.useRef();
	const tomeName = React.useRef();
	const issueNum = React.useRef();
	const additional = React.useRef();
	const place = React.useRef();
	const publish = React.useRef();
	const pagesSince = React.useRef();
	const pagesBefore = React.useRef();
	const url = React.useRef();
	const date = React.useRef();

	const [open, setOpen] = React.useState(false); // modal
	const [stroke, setStroke] = React.useState(''); // stroke

	// book ad refs
	const lang = React.useRef();
	const type = React.useRef();

	// modal refs
	const [savedClicked, setSavedClicked] = React.useState(false);
	const [saveError, setSaveError] = React.useState(false);
	const output = React.useRef();
	const formModal = React.useRef();
	const modal = React.useRef();
	const savedSuccess = React.useRef();
	const savedFail = React.useRef();

	const [isEversion, setIsEversion] = React.useState(() => {
		const e_vers_exists = storageSes.get(doc)
		if (e_vers_exists) {
			if (e_vers_exists.type === 'electronic') {
				return true
			} else {
				return false
			}
		} else return false
	});

	const [isRU, setIsRU] = React.useState(() => {
		const ru_exists = storageSes.get(doc)
		if (ru_exists) {
			const ru_exists_obj = JSON.parse(storageSes.get(doc))
			if (ru_exists_obj.lang === 'ru') {
				return true
			} else {
				return false
			}
		} else {
			return true
		}
	});

	React.useEffect(() => {

		let obj = JSON.parse(storageSes.get(doc)) // null

		if (obj) {

			let els = form.current.elements

			lang.current.value = obj.lang
			type.current.value = obj.type

			if (obj.lang === 'ru') setIsRU(true)
			if (obj.type === 'electronic') setIsEversion(true)

			for (let item of els) {
				item.value = obj[`${item.id}`] || ''
			}
		}

	}, [doc, isRU, isEversion])


	const runDoi = e => {
		let doiN = doiNumber.current.value;
		let cond = (/https:\/\/doi.org\//gi.test(doiN) ? '' : 'https://doi.org/') + doiN;
		e.target.href = cond
	}

	const saveSession = () => {

		let obj = JSON.parse(sessionStorage.getItem(doc)) || {};

		obj.lang = lang.current.value;
		obj.type = type.current.value;


		obj.type === 'electronic' ? setIsEversion(true) : setIsEversion(false);

		obj.lang === 'ru' ? setIsRU(true) : setIsRU(false);

		let els = form.current.elements;

		for (let item of els) {
			if (item.id.includes(doc)) {
				obj[`${item.id}`] = item.value || '';
			}
		}

		storageSes.set(doc, JSON.stringify(obj));
	}

	const clearForm = () => {
		form.current.reset();
		storageSes.delete(doc);
		lang.current.value = 'ru';
		type.current.value = 'printed';
		setIsEversion(false);
		setIsRU(true);
	}

	const configure = e => {
		e.preventDefault()

		setOpen(true);

		let _doi = doiNumber.current.value || '',
			_author = author.current.value || '',
			_iss_name = issueName.current.value || '',
			_col_name = nameCollection.current.value || '',
			_tome_num = tomeNum.current.value || '',
			_iss_num = issueNum.current.value || '',
			_publish = publish.current.value || '',
			_pages_s = pagesSince.current.value || '',
			_pages_b = pagesBefore.current.value || '',
			_adds = additional.current.value || '';

		let result_stroke = `<i>${_author}</i> ` + _iss_name + ' ' + _col_name + '. ' + _publish + '. ' + (isRU ? "T" : "Vol") + '. ' + _tome_num + ', № ' + _iss_num + '. ' +
			(isRU ? "С" : "P") + '. ' + _pages_s + '-' + _pages_b + '.';


		if (isEversion) {

			let _url = url.current.value || '',
				_date = date.current.getAttribute("re-date") || '';

			result_stroke += ` URL: ${_url} (${isRU ? 'дата обращения' : 'accessed'}: ${_date}).`
		}

		// если есть серия
		if (_doi) result_stroke += ` ${(/https:\/\/doi.org\//gi.test(_doi) ? '' : 'https://doi.org/') + _doi}`;

		// если есть доп. данные
		if (_adds) result_stroke += " " + _adds;

		setStroke(result_stroke);
	};

	const saveList = () => {

		const cur_arr = JSON.parse(storageLoc.get('texts')) || [];

		let stroke_exsists = !!cur_arr.find(el => el.stroke === stroke);

		if (!stroke_exsists) {
			setSaveError(false)
			setSavedClicked(true)

			let cur_obj = {
				id: Number(new Date()),
				stroke: stroke
			}

			cur_arr.push(cur_obj);
			storageLoc.set('texts', JSON.stringify(cur_arr))

		} else {
			setSavedClicked(false)
			setSaveError(true);
		}
	}

	const inputFunc = e => {
		setStroke(e.target.value);
		setSavedClicked(false);
	}

	const copyData = () => navigator.clipboard.writeText(output.current.textContent);

	const openPdf = () => formModal.current.action = process.env.REACT_APP_FETCH_PDF

	const saveTxt = (filename, text) => {
		let link = document.createElement('a')
		link.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(text.replace(/<i>/i, '').replace(/<\/i>/i, ''))
		link.setAttribute('download', filename)
		link.style.display = 'none'
		document.body.appendChild(link)
		link.click()
		document.body.removeChild(link)
	}

	return (
		<>
			{open && (
				<DocModal
					data={{
						stroke,
						saveBtn: 'Сохранить в "Мои списки"',
						savedClicked,
						saveError
					}}
					ref={{
						output,
						formModal,
						modal,
						savedSuccess,
						savedFail
					}}
					funcs={{
						copyData,
						openPdf,
						saveList,
						inputFunc,
						setSavedClicked,
						setSaveError,
						saveTxt,
						onClose: () => setOpen(false)
					}}
				/>
			)}

			<DocSettings
				ref={{ lang, type }}
				clearForm={clearForm}
				update={saveSession}
			/>

			<form
				className='collection-tab form-tab'
				onChange={saveSession}
				ref={form}
			>

				<FormInput
					ref={doiNumber}
					type='text'
					id='collection-doi'
					label='DOI (если есть, указать обязательно)'
					placeholder='10.18500/1816-9791-2016-16-3-256-262'
				/>

				<a
					type='button'
					href='https://doi.org/'
					rel="noreferrer"
					className='add-btn'
					onClick={runDoi}
					target='_blank'
				>
					Проверить
				</a>

				<FormTextarea
					ref={author}
					id="collection-author"
					label="Автор(ы)"
					rows="3"
					placeholder={isRU ? 'Водолазов А. М., Лукомский С. Ф.' : 'Bellmann R., Cooke K.'}
				/>

				<FormInput
					ref={issueName}
					type='text'
					id='collection-iss-name'
					label='Название статьи'
					placeholder={isRU ? 'Ортогональные системы сдвигов в поле p-адических чисел' : 'Differential-Difference Equations'}
				/>

				<FormTextarea
					ref={nameCollection}
					id="collection-main-name"
					label="Название сборника"
					rows="3"
					placeholder=''
				/>

				<FormInput
					ref={tomeNum}
					type='text'
					id='collection-tome-num'
					label='Номер тома'
					placeholder=''
				/>

				<FormInput
					ref={tomeName}
					type='text'
					id='collection-tome-name'
					label='Название тома'
				/>

				<FormInput
					ref={issueNum}
					type='text'
					id='collection-iss-num'
					label='Номер выпуска'
					placeholder=''
				/>

				<FormInput
					ref={place}
					type='text'
					id='collection-place'
					label='Место издания'
					placeholder={isRU ? 'Москва' : 'New York'}
				/>

				<FormInput
					ref={publish}
					type='text'
					id='collection-publish'
					label='Издательство'
					placeholder={isRU ? 'Наука' : 'Academic Press'}
				/>

				<div className="j-pages">
					Страницы:
					<FormInput
						ref={pagesSince}
						type="text"
						id="collection-s-pages"
						label="с"
						placeholder='256'
					/>
					<FormInput
						ref={pagesBefore}
						type="text"
						id="collection-po-pages"
						label="по"
						placeholder='262'
					/>
				</div>

				{
					isEversion && (
						<EVersion
							ref={{ url, date }}
							urlId='collection-e-url'
							dateId='collection-e-date'
						/>
					)
				}

				<div className="form-tab-field add-data">
					<textarea ref={additional} id="collection-additional" rows="3"></textarea>
					<label htmlFor="collection-additional">
						Дополнительные данные
					</label>
				</div>

				<SubmitBtn onClick={configure}>
					Далее
				</SubmitBtn>

			</form>
		</>
	)
}
